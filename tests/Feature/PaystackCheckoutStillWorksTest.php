<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * The Flutterwave V4 upgrade deliberately left PaymentController and
 * PaystackService untouched — this is a sanity check that the existing
 * Paystack checkout path (client-side popup -> POST /checkout/verify)
 * still works exactly as it did before that upgrade.
 */
class PaystackCheckoutStillWorksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);

        config(['services.paystack.secret_key' => 'sk_test_untouched']);
    }

    public function test_paystack_verify_still_creates_a_paid_order(): void
    {
        $product = Product::create([
            'name' => 'Test Monitor', 'description' => 'desc', 'category' => 'Monitoring',
            'price' => 12000, 'stock' => 5, 'sku' => 'SKU-'.uniqid(),
        ]);

        Http::fake([
            'api.paystack.co/*' => Http::response([
                'status' => true,
                'data' => ['status' => 'success', 'amount' => 1200000, 'currency' => 'NGN', 'reference' => 'PSK-REF-1'],
            ], 200),
        ]);

        $response = $this->postJson('/checkout/verify', [
            'gateway' => 'paystack',
            'reference' => 'PSK-REF-1',
            'cart_items' => [['id' => $product->id, 'quantity' => 1]],
            'shipping_address' => '1 Test Street, Lagos',
            'guest_name' => 'Jane Buyer',
            'guest_email' => 'buyer@example.com',
        ]);

        $response->assertOk()->assertJson(['status' => 'success']);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertSame(Order::STATUS_PAID, $order->status);
        $this->assertSame('PAYSTACK', $order->payment_method);
        $this->assertSame('PSK-REF-1', $order->payment_id);
    }

    public function test_paystack_verify_rejects_a_failed_transaction(): void
    {
        $product = Product::create([
            'name' => 'Test Monitor', 'description' => 'desc', 'category' => 'Monitoring',
            'price' => 12000, 'stock' => 5, 'sku' => 'SKU-'.uniqid(),
        ]);

        Http::fake([
            'api.paystack.co/*' => Http::response(['status' => true, 'data' => ['status' => 'failed']], 200),
        ]);

        $response = $this->postJson('/checkout/verify', [
            'gateway' => 'paystack',
            'reference' => 'PSK-REF-2',
            'cart_items' => [['id' => $product->id, 'quantity' => 1]],
            'shipping_address' => '1 Test Street, Lagos',
            'guest_name' => 'Jane Buyer',
            'guest_email' => 'buyer@example.com',
        ]);

        $response->assertStatus(400);
        $this->assertSame(0, Order::count());
    }
}
