<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\FlutterwaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Async, authoritative confirmation path — the customer's browser redirect
 * back from Flutterwave's hosted checkout (FlutterwaveCheckoutController)
 * is a best-effort UX shortcut, but a closed tab or dropped connection
 * shouldn't leave a paid order stuck PENDING, so this webhook independently
 * confirms the same order.
 *
 * Security model: the webhook payload's `status`/`type` fields are used
 * only to locate which order to look at, via `data.reference` — never to
 * decide whether to mark it paid. The actual paid/failed decision always
 * comes from an authenticated call to Flutterwave's own Verification API
 * (FlutterwaveService::verifyTransaction()), keyed by the charge id *we*
 * stored when the order was created, not whatever id the payload claims.
 * A forged (but validly signed) payload therefore can't fake a payment —
 * it can only prompt us to re-check the real transaction, which is
 * inherently safe to do.
 *
 * Order::markFlutterwavePaid()/markFlutterwaveFailed() are guarded by
 * current status, so this webhook and FlutterwaveCheckoutController's
 * redirect-back callback can race each other safely — whichever arrives
 * first wins, the other is a no-op. This route lives in routes/api.php,
 * which has no CSRF middleware, matching the existing CrmWebhookController
 * convention.
 */
class FlutterwaveWebhookController extends Controller
{
    public function __construct(private readonly FlutterwaveService $flutterwave) {}

    public function handle(Request $request): JsonResponse
    {
        $rawBody = $request->getContent();

        Log::channel('flutterwave')->info('Webhook received', [
            'ip' => $request->ip(),
            'content_length' => strlen($rawBody),
        ]);

        try {
            return $this->process($request, $rawBody);
        } catch (Throwable $e) {
            // Never leak exception details (could echo config/env) into the response.
            Log::channel('flutterwave')->error('Webhook processing threw an exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json(['received' => false, 'error' => 'internal_error'], 500);
        }
    }

    private function process(Request $request, string $rawBody): JsonResponse
    {
        $signature = $request->header('flutterwave-signature');
        $legacyHash = $request->header('verif-hash');

        if (! $this->flutterwave->verifyWebhookSignature($rawBody, $signature, $legacyHash)) {
            Log::channel('flutterwave')->warning('Invalid webhook signature — rejecting.', ['ip' => $request->ip()]);

            return response()->json(['received' => false, 'error' => 'invalid_signature'], 403);
        }

        $payload = json_decode($rawBody, true);
        $data = is_array($payload) ? ($payload['data'] ?? null) : null;
        $reference = is_array($data) ? ($data['reference'] ?? null) : null;
        $eventType = is_array($payload) ? ($payload['type'] ?? $payload['event'] ?? null) : null;

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($payload) || ! is_array($data) || ! $reference) {
            Log::channel('flutterwave')->warning('Malformed webhook payload — missing data.reference.', [
                'event_type' => $eventType,
            ]);

            return response()->json(['received' => false, 'error' => 'malformed_payload'], 422);
        }

        Log::channel('flutterwave')->info('Webhook payload parsed', [
            'reference' => $reference,
            'event_type' => $eventType,
        ]);

        $order = Order::where('tracking_code', $reference)->where('payment_method', 'FLUTTERWAVE')->first();

        if (! $order) {
            Log::channel('flutterwave')->warning('Webhook reference does not match any order.', ['reference' => $reference]);

            return response()->json(['received' => true, 'processed' => false, 'reason' => 'order_not_found']);
        }

        if (! $order->payment_id) {
            Log::channel('flutterwave')->warning('Order has no stored charge id to verify against.', ['tracking_code' => $order->tracking_code]);

            return response()->json(['received' => true, 'processed' => false, 'reason' => 'no_charge_id']);
        }

        if ($order->hasProcessedFlutterwaveTransaction($order->payment_id)) {
            Log::channel('flutterwave')->info('Duplicate webhook — order already processed, no changes made.', [
                'tracking_code' => $order->tracking_code,
                'flutterwave_transaction_id' => $order->flutterwave_transaction_id,
            ]);

            return response()->json(['received' => true, 'processed' => false, 'reason' => 'duplicate']);
        }

        if ($order->status !== Order::STATUS_PENDING) {
            // Already finalized some other way (e.g. the redirect callback beat us here).
            Log::channel('flutterwave')->info('Order already finalized — no changes made.', [
                'tracking_code' => $order->tracking_code, 'status' => $order->status,
            ]);

            return response()->json(['received' => true, 'processed' => false, 'reason' => 'already_finalized']);
        }

        // Always re-verify against Flutterwave's own API using *our* stored charge id —
        // the webhook payload's status/amount are never trusted for the actual decision.
        Log::channel('flutterwave')->info('Requesting verification', ['tracking_code' => $order->tracking_code]);
        $result = $this->flutterwave->verifyTransaction($order->payment_id);
        Log::channel('flutterwave')->info('Verification response received', [
            'tracking_code' => $order->tracking_code,
            'successful' => $result['successful'] ?? false,
            'status' => $result['status'] ?? null,
        ]);

        if (empty($result['successful'])) {
            Log::channel('flutterwave')->warning('Verification did not confirm payment — order not updated.', [
                'tracking_code' => $order->tracking_code,
            ]);

            $order->markFlutterwaveFailed($result);

            return response()->json(['received' => true, 'processed' => false, 'reason' => 'not_verified']);
        }

        if (! $order->verifiedAmountMatches($result)) {
            Log::channel('flutterwave')->error('Verified amount/currency does not match the order — refusing to mark paid.', [
                'tracking_code' => $order->tracking_code,
                'order_total' => (string) $order->total_amount,
                'verified_amount' => $result['amount'] ?? null,
                'verified_currency' => $result['currency'] ?? null,
            ]);

            return response()->json(['received' => false, 'error' => 'amount_mismatch'], 422);
        }

        if (
            $order->customerEmail()
            && ! empty($result['customer_email'])
            && strcasecmp((string) $order->customerEmail(), (string) $result['customer_email']) !== 0
        ) {
            // Logged for visibility only — not blocked, since a legitimate customer can
            // pay with a card registered under a different email than their checkout form.
            Log::channel('flutterwave')->warning('Verified customer email differs from the order — proceeding, flagged for review.', [
                'tracking_code' => $order->tracking_code,
            ]);
        }

        $order->markFlutterwavePaid($result);

        Log::channel('flutterwave')->info('Order marked paid.', [
            'tracking_code' => $order->tracking_code,
            'flutterwave_transaction_id' => $order->flutterwave_transaction_id,
        ]);

        return response()->json(['received' => true, 'processed' => true]);
    }
}
