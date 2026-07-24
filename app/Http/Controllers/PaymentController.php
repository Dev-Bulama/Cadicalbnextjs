<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\FlutterwaveService;
use App\Services\PaystackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct(
        private readonly FlutterwaveService $flutterwave,
        private readonly PaystackService $paystack,
    ) {}

    public function verify(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'gateway' => ['required', 'in:flutterwave,paystack'],
            'reference' => ['required', 'string'],
            'cart_items' => ['required', 'array', 'min:1'],
            'cart_items.*.id' => ['required', 'integer', 'exists:products,id'],
            'cart_items.*.quantity' => ['required', 'integer', 'min:1'],
            'shipping_address' => ['required', 'string'],
            'guest_name' => [$user ? 'nullable' : 'required', 'string', 'max:255'],
            'guest_email' => [$user ? 'nullable' : 'required', 'email', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:30'],
        ]);

        $result = $validated['gateway'] === 'paystack'
            ? $this->paystack->verifyTransaction($validated['reference'])
            : $this->flutterwave->verifyTransaction($validated['reference']);

        if (! $result['successful']) {
            return response()->json(['status' => 'failed'], 400);
        }

        // Recompute totals server-side from real product prices — never trust client-supplied amounts.
        $products = Product::whereIn('id', collect($validated['cart_items'])->pluck('id'))->get()->keyBy('id');

        $order = DB::transaction(function () use ($validated, $user, $products, $result) {
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
                'payment_id' => $result['reference'],
                'payment_method' => strtoupper($validated['gateway']),
                'shipping_address' => $validated['shipping_address'],
                'status' => Order::STATUS_PAID,
            ]);

            foreach ($lineItems as $line) {
                OrderItem::create(array_merge($line, ['order_id' => $order->id]));
            }

            $order->trackingEvents()->create([
                'status' => 'PAID',
                'message' => 'Payment received and order confirmed',
            ]);

            return $order;
        });

        return response()->json([
            'status' => 'success',
            'order' => $order->load('orderItems'),
        ]);
    }
}
