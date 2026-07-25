<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\FlutterwaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Async, authoritative confirmation path — the customer's browser redirect
 * back from Flutterwave's hosted checkout (FlutterwaveCheckoutController)
 * is a best-effort UX shortcut, but a closed tab or dropped connection
 * shouldn't leave a paid order stuck PENDING, so this webhook independently
 * verifies and finalizes the same order. Order::markFlutterwavePaid()/
 * markFlutterwaveFailed() are guarded by current status, so whichever path
 * (webhook or redirect) arrives first wins and the other is a safe no-op —
 * this route is registered in routes/api.php, which has no CSRF middleware,
 * matching the existing CrmWebhookController convention.
 */
class FlutterwaveWebhookController extends Controller
{
    public function __construct(private readonly FlutterwaveService $flutterwave) {}

    public function handle(Request $request): JsonResponse
    {
        $rawBody = $request->getContent();
        $signature = $request->header('flutterwave-signature');
        $legacyHash = $request->header('verif-hash');

        if (! $this->flutterwave->verifyWebhookSignature($rawBody, $signature, $legacyHash)) {
            Log::warning('Flutterwave webhook signature verification failed — rejecting.');

            return response()->json(['received' => false], 401);
        }

        $payload = $request->json()->all();
        $data = $payload['data'] ?? [];
        $eventType = $payload['type'] ?? $payload['event'] ?? null;
        $reference = $data['reference'] ?? null;
        $status = $data['status'] ?? null;

        if (! $reference) {
            return response()->json(['received' => true]);
        }

        $order = Order::where('tracking_code', $reference)->where('payment_method', 'FLUTTERWAVE')->first();

        if (! $order) {
            return response()->json(['received' => true]);
        }

        $succeeded = $status === 'succeeded' || $eventType === 'charge.completed';
        $failed = $status === 'failed' || $eventType === 'charge.failed';

        if ($succeeded) {
            $order->markFlutterwavePaid();
        } elseif ($failed) {
            $order->markFlutterwaveFailed();
        }

        return response()->json(['received' => true]);
    }
}
