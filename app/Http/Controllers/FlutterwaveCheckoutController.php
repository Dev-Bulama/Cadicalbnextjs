<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\FlutterwaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Flutterwave V4 has no client-side widget or public key — payment happens
 * on Flutterwave's own hosted page, so the browser has to navigate away and
 * back, unlike Paystack's in-page popup (untouched, see PaymentController).
 * That round trip means shipping/cart details can't just live in the page's
 * JS state anymore, so we create a PENDING Order (with its items already
 * priced server-side) before redirecting, then finalize it here — and again,
 * idempotently, from FlutterwaveWebhookController — once payment completes.
 */
class FlutterwaveCheckoutController extends Controller
{
    public function __construct(private readonly FlutterwaveService $flutterwave) {}

    public function initiate(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'cart_items' => ['required', 'array', 'min:1'],
            'cart_items.*.id' => ['required', 'integer', 'exists:products,id'],
            'cart_items.*.quantity' => ['required', 'integer', 'min:1'],
            'shipping_address' => ['required', 'string'],
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'guest_name' => [$user ? 'nullable' : 'required', 'string', 'max:255'],
            'guest_email' => [$user ? 'nullable' : 'required', 'email', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:30'],
        ]);

        if (! $this->flutterwave->isConfigured()) {
            return response()->json(['status' => 'error', 'message' => 'Flutterwave is not configured yet. Try Paystack, or contact support.'], 422);
        }

        // Recompute totals server-side from real product prices — never trust client-supplied amounts.
        $products = Product::whereIn('id', collect($validated['cart_items'])->pluck('id'))->get()->keyBy('id');

        $order = DB::transaction(function () use ($validated, $user, $products) {
            $totalAmount = 0;
            $lineItems = [];

            foreach ($validated['cart_items'] as $item) {
                $product = $products->get($item['id']);
                if (! $product) {
                    continue;
                }
                $lineTotal = $product->price * $item['quantity'];
                $totalAmount += $lineTotal;
                $lineItems[] = ['product_id' => $product->id, 'quantity' => $item['quantity'], 'price' => $product->price];
            }

            $order = Order::create([
                'user_id' => $user?->id,
                'guest_name' => $user ? null : $validated['guest_name'],
                'guest_email' => $user ? null : $validated['guest_email'],
                'guest_phone' => $user ? null : ($validated['guest_phone'] ?? null),
                'total_amount' => $totalAmount,
                'tracking_code' => Order::generateTrackingCode(),
                'payment_method' => 'FLUTTERWAVE',
                'shipping_address' => $validated['shipping_address'],
                'status' => Order::STATUS_PENDING,
            ]);

            foreach ($lineItems as $line) {
                OrderItem::create(array_merge($line, ['order_id' => $order->id]));
            }

            return $order;
        });

        if ($order->total_amount <= 0) {
            $order->delete();

            return response()->json(['status' => 'error', 'message' => 'Your cart total is invalid.'], 422);
        }

        $charge = $this->flutterwave->initiateCharge([
            'amount' => (float) $order->total_amount,
            'currency' => 'NGN',
            'reference' => $order->tracking_code,
            'email' => $validated['email'],
            'name' => $validated['name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'redirect_url' => route('checkout.flutterwave.callback', ['ref' => $order->tracking_code]),
        ]);

        if (! $charge['successful'] || empty($charge['checkout_url'])) {
            Log::channel('flutterwave')->warning('Flutterwave charge initiation failed', ['tracking_code' => $order->tracking_code]);
            $order->delete();

            return response()->json(['status' => 'error', 'message' => 'Could not start Flutterwave payment. Please try again or use Paystack.'], 502);
        }

        $order->update(['payment_id' => $charge['id']]);

        return response()->json(['status' => 'ok', 'checkout_url' => $charge['checkout_url']]);
    }

    public function callback(Request $request, string $ref): RedirectResponse
    {
        $order = Order::where('tracking_code', $ref)->where('payment_method', 'FLUTTERWAVE')->first();

        if (! $order) {
            Log::channel('flutterwave')->warning('Redirect callback for unknown order reference.', ['ref' => $ref]);

            return redirect()->to('/checkout?flutterwave=missing');
        }

        if ($order->status === Order::STATUS_PENDING && $order->payment_id) {
            $result = $this->flutterwave->verifyTransaction($order->payment_id);

            if (! empty($result['successful']) && $order->verifiedAmountMatches($result)) {
                $order->markFlutterwavePaid($result);
            } else {
                if (! empty($result['successful'])) {
                    Log::channel('flutterwave')->error('Redirect callback: verified amount/currency mismatch — refusing to mark paid.', [
                        'tracking_code' => $order->tracking_code,
                    ]);
                }
                $order->markFlutterwaveFailed($result);
            }
        }

        if ($order->fresh()->status === Order::STATUS_PAID) {
            return redirect()->to('/checkout?'.http_build_query([
                'flutterwave' => 'success',
                'tracking_code' => $order->tracking_code,
                'total' => (string) $order->total_amount,
            ]));
        }

        return redirect()->to('/checkout?flutterwave=failed');
    }
}
