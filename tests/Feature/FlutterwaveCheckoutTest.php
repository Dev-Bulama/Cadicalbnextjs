<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FlutterwaveCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);

        config([
            'services.flutterwave.client_id' => 'test-client-id',
            'services.flutterwave.client_secret' => 'test-client-secret',
            'services.flutterwave.webhook_secret' => 'test-webhook-secret',
        ]);
    }

    private function makeProduct(float $price = 25000): Product
    {
        return Product::create([
            'name' => 'Test Ventilator',
            'description' => 'A test product',
            'category' => 'ICU',
            'price' => $price,
            'stock' => 10,
            'sku' => 'SKU-'.uniqid(),
        ]);
    }

    private function initiatePayload(Product $product): array
    {
        return [
            'cart_items' => [['id' => $product->id, 'quantity' => 1]],
            'shipping_address' => '1 Test Street, Lagos',
            'email' => 'buyer@example.com',
            'name' => 'Jane Buyer',
            'phone' => '08010000000',
            'guest_name' => 'Jane Buyer',
            'guest_email' => 'buyer@example.com',
        ];
    }

    public function test_initiate_creates_a_pending_order_and_returns_a_checkout_url(): void
    {
        $product = $this->makeProduct(15000);

        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response([
                'id' => 'chg_abc', 'status' => 'pending', 'redirect_url' => 'https://checkout.flutterwave.com/pay/xyz',
            ], 201),
        ]);

        $response = $this->postJson('/checkout/flutterwave/initiate', $this->initiatePayload($product));

        $response->assertOk()->assertJson(['status' => 'ok', 'checkout_url' => 'https://checkout.flutterwave.com/pay/xyz']);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertSame(Order::STATUS_PENDING, $order->status);
        $this->assertSame('chg_abc', $order->payment_id);
        $this->assertEquals(15000, $order->total_amount);
        $this->assertCount(1, $order->orderItems);
    }

    public function test_initiate_fails_cleanly_and_creates_no_order_when_flutterwave_is_not_configured(): void
    {
        config(['services.flutterwave.client_id' => null, 'services.flutterwave.client_secret' => null]);
        $product = $this->makeProduct();

        $response = $this->postJson('/checkout/flutterwave/initiate', $this->initiatePayload($product));

        $response->assertStatus(422);
        $this->assertSame(0, Order::count());
    }

    public function test_initiate_rolls_back_the_pending_order_when_flutterwave_rejects_the_charge(): void
    {
        $product = $this->makeProduct();

        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response(['message' => 'bad request'], 400),
        ]);

        $response = $this->postJson('/checkout/flutterwave/initiate', $this->initiatePayload($product));

        $response->assertStatus(502);
        $this->assertSame(0, Order::count());
    }

    public function test_callback_marks_the_order_paid_on_a_successful_charge(): void
    {
        $product = $this->makeProduct(10000);

        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response(['id' => 'chg_1', 'status' => 'pending', 'redirect_url' => 'https://x/pay'], 201),
        ]);
        $this->postJson('/checkout/flutterwave/initiate', $this->initiatePayload($product));
        $order = Order::first();

        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges/chg_1' => Http::response(['id' => 'chg_1', 'status' => 'succeeded', 'amount' => 10000], 200),
        ]);

        $response = $this->get('/checkout/flutterwave/callback/'.$order->tracking_code);

        $response->assertRedirect();
        $this->assertStringContainsString('flutterwave=success', $response->headers->get('Location'));
        $this->assertSame(Order::STATUS_PAID, $order->fresh()->status);
    }

    public function test_callback_marks_the_order_cancelled_on_a_failed_charge(): void
    {
        $product = $this->makeProduct(10000);

        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response(['id' => 'chg_2', 'status' => 'pending', 'redirect_url' => 'https://x/pay'], 201),
        ]);
        $this->postJson('/checkout/flutterwave/initiate', $this->initiatePayload($product));
        $order = Order::first();

        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges/chg_2' => Http::response(['id' => 'chg_2', 'status' => 'failed'], 200),
        ]);

        $response = $this->get('/checkout/flutterwave/callback/'.$order->tracking_code);

        $response->assertRedirect();
        $this->assertStringContainsString('flutterwave=failed', $response->headers->get('Location'));
        $this->assertSame(Order::STATUS_CANCELLED, $order->fresh()->status);
    }

    public function test_callback_redirects_gracefully_for_an_unknown_reference(): void
    {
        $response = $this->get('/checkout/flutterwave/callback/TRK-DOES-NOT-EXIST');

        $response->assertRedirect();
        $this->assertStringContainsString('flutterwave=missing', $response->headers->get('Location'));
    }

    private function postWebhook(string $body, ?string $signature): \Illuminate\Testing\TestResponse
    {
        return $this->call('POST', '/api/webhooks/flutterwave', [], [], [], array_filter([
            'HTTP_flutterwave-signature' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ]), $body);
    }

    public function test_webhook_finalizes_a_pending_order_with_a_valid_signature(): void
    {
        $product = $this->makeProduct(10000);
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response(['id' => 'chg_3', 'status' => 'pending', 'redirect_url' => 'https://x/pay'], 201),
        ]);
        $this->postJson('/checkout/flutterwave/initiate', $this->initiatePayload($product));
        $order = Order::first();

        // The webhook payload alone is never trusted — the controller always re-verifies
        // via this endpoint using the charge id *we* stored, regardless of what the
        // payload's own status field claims.
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges/chg_3' => Http::response(['id' => 'chg_3', 'status' => 'succeeded', 'amount' => 10000, 'currency' => 'NGN'], 200),
        ]);

        $body = json_encode(['type' => 'charge.completed', 'data' => ['reference' => $order->tracking_code, 'status' => 'succeeded']]);
        $signature = hash_hmac('sha256', $body, 'test-webhook-secret');

        $this->postWebhook($body, $signature)->assertOk()->assertJson(['received' => true, 'processed' => true]);

        $order->refresh();
        $this->assertSame(Order::STATUS_PAID, $order->status);
        $this->assertSame('chg_3', $order->flutterwave_transaction_id);
        $this->assertNotNull($order->paid_at);
    }

    public function test_webhook_ignores_the_payloads_claimed_status_and_trusts_only_the_verification_api(): void
    {
        $product = $this->makeProduct(10000);
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response(['id' => 'chg_7', 'status' => 'pending', 'redirect_url' => 'https://x/pay'], 201),
        ]);
        $this->postJson('/checkout/flutterwave/initiate', $this->initiatePayload($product));
        $order = Order::first();

        // A payload *claiming* success, but the authoritative verification API says otherwise.
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges/chg_7' => Http::response(['id' => 'chg_7', 'status' => 'pending'], 200),
        ]);

        $body = json_encode(['type' => 'charge.completed', 'data' => ['reference' => $order->tracking_code, 'status' => 'succeeded']]);
        $signature = hash_hmac('sha256', $body, 'test-webhook-secret');

        $this->postWebhook($body, $signature)->assertOk()->assertJson(['received' => true, 'processed' => false]);

        $this->assertSame(Order::STATUS_CANCELLED, $order->fresh()->status);
    }

    public function test_webhook_refuses_to_mark_paid_when_verified_amount_does_not_match_the_order(): void
    {
        $product = $this->makeProduct(10000);
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response(['id' => 'chg_8', 'status' => 'pending', 'redirect_url' => 'https://x/pay'], 201),
        ]);
        $this->postJson('/checkout/flutterwave/initiate', $this->initiatePayload($product));
        $order = Order::first();

        // Verification says "succeeded", but for a different amount than the order total —
        // never trust the payload's amount, but also never trust a mismatched verified amount.
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges/chg_8' => Http::response(['id' => 'chg_8', 'status' => 'succeeded', 'amount' => 1, 'currency' => 'NGN'], 200),
        ]);

        $body = json_encode(['type' => 'charge.completed', 'data' => ['reference' => $order->tracking_code, 'status' => 'succeeded']]);
        $signature = hash_hmac('sha256', $body, 'test-webhook-secret');

        $this->postWebhook($body, $signature)->assertStatus(422)->assertJson(['error' => 'amount_mismatch']);

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
    }

    public function test_webhook_returns_422_for_a_malformed_payload(): void
    {
        $body = json_encode(['type' => 'charge.completed']); // no data.reference at all

        $response = $this->postWebhook($body, hash_hmac('sha256', $body, 'test-webhook-secret'));

        $response->assertStatus(422)->assertJson(['error' => 'malformed_payload']);
    }

    public function test_webhook_acknowledges_but_does_nothing_for_an_unknown_order_reference(): void
    {
        $body = json_encode(['type' => 'charge.completed', 'data' => ['reference' => 'TRK-DOES-NOT-EXIST', 'status' => 'succeeded']]);
        $signature = hash_hmac('sha256', $body, 'test-webhook-secret');

        $this->postWebhook($body, $signature)
            ->assertOk()
            ->assertJson(['received' => true, 'processed' => false, 'reason' => 'order_not_found']);
    }

    public function test_webhook_acknowledges_but_does_nothing_when_the_order_has_no_stored_charge_id(): void
    {
        $order = Order::create([
            'total_amount' => 10000,
            'tracking_code' => Order::generateTrackingCode(),
            'payment_method' => 'FLUTTERWAVE',
            'shipping_address' => 'addr',
            'status' => Order::STATUS_PENDING,
            'guest_name' => 'Jane', 'guest_email' => 'buyer@example.com',
            // payment_id intentionally left null — e.g. initiate() crashed after creating
            // the order but before Flutterwave returned a charge id.
        ]);

        $body = json_encode(['type' => 'charge.completed', 'data' => ['reference' => $order->tracking_code, 'status' => 'succeeded']]);
        $signature = hash_hmac('sha256', $body, 'test-webhook-secret');

        $this->postWebhook($body, $signature)
            ->assertOk()
            ->assertJson(['received' => true, 'processed' => false, 'reason' => 'no_charge_id']);

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
    }

    public function test_webhook_rejects_an_invalid_signature_and_does_not_touch_the_order(): void
    {
        $product = $this->makeProduct(10000);
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response(['id' => 'chg_5', 'status' => 'pending', 'redirect_url' => 'https://x/pay'], 201),
        ]);
        $this->postJson('/checkout/flutterwave/initiate', $this->initiatePayload($product));
        $order = Order::first();

        $body = json_encode(['type' => 'charge.completed', 'data' => ['reference' => $order->tracking_code, 'status' => 'succeeded']]);

        $this->postWebhook($body, 'not-a-real-signature')->assertStatus(403)->assertJson(['error' => 'invalid_signature']);

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
    }

    public function test_duplicate_webhook_delivery_is_idempotent(): void
    {
        $product = $this->makeProduct(10000);
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response(['id' => 'chg_4', 'status' => 'pending', 'redirect_url' => 'https://x/pay'], 201),
        ]);
        $this->postJson('/checkout/flutterwave/initiate', $this->initiatePayload($product));
        $order = Order::first();

        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges/chg_4' => Http::response(['id' => 'chg_4', 'status' => 'succeeded', 'amount' => 10000, 'currency' => 'NGN'], 200),
        ]);

        $body = json_encode(['type' => 'charge.completed', 'data' => ['reference' => $order->tracking_code, 'status' => 'succeeded']]);
        $signature = hash_hmac('sha256', $body, 'test-webhook-secret');

        $this->postWebhook($body, $signature)->assertOk()->assertJson(['processed' => true]);
        $this->assertCount(1, $order->fresh()->trackingEvents);

        // Flutterwave (or any at-least-once webhook sender) may redeliver the same event.
        $this->postWebhook($body, $signature)->assertOk()->assertJson(['received' => true, 'processed' => false, 'reason' => 'duplicate']);

        $this->assertCount(1, $order->fresh()->trackingEvents);
        $this->assertSame(Order::STATUS_PAID, $order->fresh()->status);
    }

    public function test_webhook_and_redirect_callback_racing_each_other_is_idempotent(): void
    {
        $product = $this->makeProduct(10000);
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response(['id' => 'chg_6', 'status' => 'pending', 'redirect_url' => 'https://x/pay'], 201),
        ]);
        $this->postJson('/checkout/flutterwave/initiate', $this->initiatePayload($product));
        $order = Order::first();

        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges/chg_6' => Http::response(['id' => 'chg_6', 'status' => 'succeeded', 'amount' => 10000, 'currency' => 'NGN'], 200),
        ]);

        // Webhook arrives first.
        $body = json_encode(['type' => 'charge.completed', 'data' => ['reference' => $order->tracking_code, 'status' => 'succeeded']]);
        $signature = hash_hmac('sha256', $body, 'test-webhook-secret');
        $this->postWebhook($body, $signature)->assertOk()->assertJson(['processed' => true]);

        // Customer's browser redirect back lands second — verifyTransaction() would report
        // succeeded too, but markFlutterwavePaid() must no-op since it's no longer PENDING.
        $this->get('/checkout/flutterwave/callback/'.$order->tracking_code)->assertRedirect();

        $this->assertCount(1, $order->fresh()->trackingEvents);
        $this->assertSame(Order::STATUS_PAID, $order->fresh()->status);
    }
}
