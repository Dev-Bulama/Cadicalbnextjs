<?php

namespace Tests\Unit;

use App\Services\FlutterwaveService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FlutterwaveServiceTest extends TestCase
{
    private FlutterwaveService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new FlutterwaveService;

        config([
            'services.flutterwave.client_id' => 'test-client-id',
            'services.flutterwave.client_secret' => 'test-client-secret',
            'services.flutterwave.oauth_url' => 'https://idp.flutterwave.com/realms/flutterwave/protocol/openid-connect/token',
            'services.flutterwave.base_url' => 'https://api.flutterwave.cloud/f4b/production',
            'services.flutterwave.webhook_secret' => 'test-webhook-secret',
        ]);
    }

    public function test_generates_and_caches_an_oauth_token(): void
    {
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'token-abc', 'expires_in' => 3600], 200),
        ]);

        $this->assertSame('token-abc', $this->service->getAccessToken());
        $this->assertSame('token-abc', $this->service->getAccessToken());

        Http::assertSentCount(1); // second call served from cache
    }

    public function test_force_refresh_bypasses_the_cache_and_fetches_a_new_token(): void
    {
        Http::fake([
            'idp.flutterwave.com/*' => Http::sequence()
                ->push(['access_token' => 'token-1', 'expires_in' => 3600], 200)
                ->push(['access_token' => 'token-2', 'expires_in' => 3600], 200),
        ]);

        $this->assertSame('token-1', $this->service->getAccessToken());
        $this->assertSame('token-2', $this->service->getAccessToken(forceRefresh: true));

        Http::assertSentCount(2);
    }

    public function test_invalid_credentials_return_no_token(): void
    {
        Http::fake(['idp.flutterwave.com/*' => Http::response(['error' => 'invalid_client'], 401)]);

        $this->assertNull($this->service->getAccessToken());
    }

    public function test_missing_credentials_never_call_the_network(): void
    {
        config(['services.flutterwave.client_id' => null, 'services.flutterwave.client_secret' => null]);
        Http::fake();

        $this->assertNull($this->service->getAccessToken());
        Http::assertNothingSent();
    }

    public function test_connection_test_reports_true_for_valid_credentials(): void
    {
        Http::fake(['idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200)]);

        $this->assertTrue($this->service->testConnection('some-id', 'some-secret'));
    }

    public function test_connection_test_reports_false_for_invalid_credentials(): void
    {
        Http::fake(['idp.flutterwave.com/*' => Http::response(['error' => 'invalid_client'], 401)]);

        $this->assertFalse($this->service->testConnection('some-id', 'bad-secret'));
    }

    public function test_initiate_charge_returns_a_checkout_url_on_success(): void
    {
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response([
                'id' => 'chg_123', 'reference' => 'TRK-ABC', 'status' => 'pending',
                'redirect_url' => 'https://checkout.flutterwave.com/pay/abc',
            ], 201),
        ]);

        $result = $this->service->initiateCharge([
            'amount' => 5000, 'currency' => 'NGN', 'reference' => 'TRK-ABC',
            'email' => 'buyer@example.com', 'redirect_url' => 'https://example.com/callback',
        ]);

        $this->assertTrue($result['successful']);
        $this->assertSame('chg_123', $result['id']);
        $this->assertSame('https://checkout.flutterwave.com/pay/abc', $result['checkout_url']);
    }

    public function test_initiate_charge_fails_gracefully_on_an_api_error(): void
    {
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges' => Http::response(['message' => 'bad request'], 400),
        ]);

        $result = $this->service->initiateCharge([
            'amount' => 5000, 'currency' => 'NGN', 'reference' => 'TRK-ABC',
            'email' => 'buyer@example.com', 'redirect_url' => 'https://example.com/callback',
        ]);

        $this->assertFalse($result['successful']);
    }

    public function test_verify_transaction_succeeds_for_a_succeeded_charge(): void
    {
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges/chg_123' => Http::response([
                'id' => 'chg_123', 'status' => 'succeeded', 'amount' => 5000, 'currency' => 'NGN', 'reference' => 'TRK-ABC',
            ], 200),
        ]);

        $result = $this->service->verifyTransaction('chg_123');

        $this->assertTrue($result['successful']);
        $this->assertEquals(5000, $result['amount']);
    }

    public function test_verify_transaction_fails_for_a_failed_charge(): void
    {
        Http::fake([
            'idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges/chg_123' => Http::response(['id' => 'chg_123', 'status' => 'failed'], 200),
        ]);

        $this->assertFalse($this->service->verifyTransaction('chg_123')['successful']);
    }

    public function test_expired_token_triggers_one_automatic_retry(): void
    {
        Http::fake([
            'idp.flutterwave.com/*' => Http::sequence()
                ->push(['access_token' => 'stale-token', 'expires_in' => 3600], 200)
                ->push(['access_token' => 'fresh-token', 'expires_in' => 3600], 200),
            'api.flutterwave.cloud/*/charges/chg_123' => Http::sequence()
                ->push(['message' => 'unauthorized'], 401)
                ->push(['id' => 'chg_123', 'status' => 'succeeded', 'amount' => 5000], 200),
        ]);

        $result = $this->service->verifyTransaction('chg_123');

        $this->assertTrue($result['successful']);
        Http::assertSentCount(4); // token, charge(401), refreshed token, charge(200)
    }

    public function test_webhook_signature_verification_with_hmac_header(): void
    {
        $body = json_encode(['type' => 'charge.completed', 'data' => ['reference' => 'TRK-ABC', 'status' => 'succeeded']]);
        $validSignature = hash_hmac('sha256', $body, 'test-webhook-secret');

        $this->assertTrue($this->service->verifyWebhookSignature($body, $validSignature, null));
        $this->assertFalse($this->service->verifyWebhookSignature($body, 'wrong-signature', null));
    }

    public function test_webhook_signature_verification_with_legacy_hash_header(): void
    {
        $body = json_encode(['type' => 'charge.completed']);

        $this->assertTrue($this->service->verifyWebhookSignature($body, null, 'test-webhook-secret'));
        $this->assertFalse($this->service->verifyWebhookSignature($body, null, 'wrong-hash'));
    }

    public function test_webhook_rejected_when_no_secret_hash_is_configured(): void
    {
        config(['services.flutterwave.webhook_secret' => null]);

        $this->assertFalse($this->service->verifyWebhookSignature('{}', 'anything', null));
    }
}
