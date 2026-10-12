<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Flutterwave V4 client: OAuth2 client-credentials auth (Client ID/Secret,
 * no more public/secret key pair), the Charges API for payment
 * initiation/verification, and webhook signature verification.
 *
 * Credentials can be passed explicitly (used by the admin "Test Connection"
 * button, which tests values the admin just typed but may not have saved
 * yet) or omitted to fall back to the configured/saved credentials used by
 * real checkout traffic.
 */
class FlutterwaveService
{
    /** Refresh a little before actual expiry so a cached token never dies mid-request. */
    private const TOKEN_EXPIRY_BUFFER_SECONDS = 30;

    private const TOKEN_MIN_TTL_SECONDS = 30;

    public function isConfigured(?string $clientId = null, ?string $clientSecret = null): bool
    {
        return filled($clientId ?? config('services.flutterwave.client_id'))
            && filled($clientSecret ?? config('services.flutterwave.client_secret'));
    }

    /**
     * Returns a cached OAuth Bearer token, transparently fetching/refreshing
     * one from Flutterwave's identity provider when missing or expired.
     * Never returns or logs the client secret.
     */
    public function getAccessToken(?string $clientId = null, ?string $clientSecret = null, bool $forceRefresh = false): ?string
    {
        $clientId ??= config('services.flutterwave.client_id');
        $clientSecret ??= config('services.flutterwave.client_secret');

        if (! $this->isConfigured($clientId, $clientSecret)) {
            return null;
        }

        $cacheKey = 'flutterwave.oauth_token.'.md5($clientId);

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        } else {
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return $cached;
            }
        }

        return $this->requestNewToken($clientId, $clientSecret, $cacheKey);
    }

    /**
     * Live OAuth check used by the admin "Test Connection" button. Always
     * bypasses the cache — a stale cached token from previously-good
     * credentials would otherwise mask a broken new Client ID/Secret pair.
     * Never persists anything; the caller decides separately whether to save.
     */
    public function testConnection(?string $clientId = null, ?string $clientSecret = null): bool
    {
        return (bool) $this->getAccessToken($clientId, $clientSecret, forceRefresh: true);
    }

    /**
     * Consolidated status behind the admin "Test Connection" button: OAuth
     * reachability (with the given, or configured, credentials) AND whether
     * a webhook secret is set — both are required for the integration to
     * actually take real payments end-to-end, not just authenticate.
     *
     * @return array{oauth_connected: bool, webhook_secret_configured: bool, ready: bool, message: string}
     */
    public function runDiagnostics(?string $clientId = null, ?string $clientSecret = null): array
    {
        $oauthConnected = $this->testConnection($clientId, $clientSecret);
        $webhookConfigured = filled(config('services.flutterwave.webhook_secret'));
        $ready = $oauthConnected && $webhookConfigured;

        $message = match (true) {
            $ready => '✔ Everything Ready — OAuth connected and webhook secret configured.',
            ! $oauthConnected && ! $webhookConfigured => '✗ Authentication failed, and no webhook secret is configured.',
            ! $oauthConnected => '✗ Authentication failed — check your Client ID and Secret.',
            default => '⚠ OAuth connected, but no webhook secret is configured yet — webhooks will be rejected until you set one.',
        };

        return [
            'oauth_connected' => $oauthConnected,
            'webhook_secret_configured' => $webhookConfigured,
            'ready' => $ready,
            'message' => $message,
        ];
    }

    private function requestNewToken(string $clientId, string $clientSecret, string $cacheKey): ?string
    {
        try {
            $response = Http::asForm()->timeout(10)->post(config('services.flutterwave.oauth_url'), [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'client_credentials',
            ]);

            if (! $response->successful()) {
                Log::channel('flutterwave')->warning('Flutterwave OAuth token request failed', [
                    'status' => $response->status(),
                    'response' => $response->json() ?? $response->body(),
                ]);

                return null;
            }

            $data = $response->json();
            $token = $data['access_token'] ?? null;

            if (! $token) {
                Log::channel('flutterwave')->warning('Flutterwave OAuth response missing access_token');

                return null;
            }

            $expiresIn = (int) ($data['expires_in'] ?? 0);
            $ttl = max(self::TOKEN_MIN_TTL_SECONDS, $expiresIn - self::TOKEN_EXPIRY_BUFFER_SECONDS);

            Cache::put($cacheKey, $token, $ttl);

            return $token;
        } catch (Throwable $e) {
            Log::channel('flutterwave')->error('Flutterwave OAuth token request threw an exception', ['message' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Creates a charge and returns the hosted checkout URL the customer
     * should be redirected to. Supports card, bank transfer, USSD, QR, bank
     * account, mobile money and Apple Pay — Flutterwave's hosted page
     * presents whichever of those are enabled on the merchant account, so
     * we don't need to hardcode a payment_method type for a general
     * checkout (only pass one when the caller wants to force a specific
     * method).
     *
     * @param  array{amount: float|int, currency?: string, reference: string, email: string, name?: string, phone?: string, redirect_url: string, payment_method_type?: string}  $params
     * @return array{successful: bool, id?: string, reference?: string, status?: string, checkout_url?: string, message?: string, raw?: array}
     */
    public function initiateCharge(array $params): array
    {
        if (! $this->isConfigured()) {
            return ['successful' => false, 'message' => 'Flutterwave is not configured.'];
        }

        $payload = array_filter([
            'amount' => $params['amount'],
            'currency' => $params['currency'] ?? 'NGN',
            'reference' => $params['reference'],
            'redirect_url' => $params['redirect_url'],
            'customer' => array_filter([
                'email' => $params['email'] ?? null,
                'name' => $params['name'] ?? null,
                'phone' => $params['phone'] ?? null,
            ]),
            'payment_method' => isset($params['payment_method_type'])
                ? ['type' => $params['payment_method_type']]
                : null,
        ], fn ($v) => $v !== null && $v !== []);

        $data = $this->request('post', 'charges', $payload);

        if (! $data || empty($data['id'])) {
            return ['successful' => false, 'message' => 'Could not initiate Flutterwave charge.'];
        }

        return [
            'successful' => true,
            'id' => $data['id'],
            'reference' => $data['reference'] ?? $params['reference'],
            'status' => $data['status'] ?? null,
            'checkout_url' => $data['redirect_url'] ?? null,
            'raw' => $data,
        ];
    }

    /**
     * Verifies a charge by Flutterwave's own charge id (returned as `id`
     * from initiateCharge() and passed back to us — never trust the
     * frontend/redirect alone, this always calls Flutterwave's API).
     *
     * @return array{successful: bool, id?: string, status?: string, amount?: float, currency?: string, reference?: string, customer_email?: string, raw?: array}
     */
    public function verifyTransaction(string $chargeId): array
    {
        if (! $this->isConfigured()) {
            return ['successful' => false];
        }

        $data = $this->request('get', 'charges/'.$chargeId);

        $successful = $data && (($data['status'] ?? null) === 'succeeded');

        return [
            'successful' => $successful,
            'id' => $data['id'] ?? $chargeId,
            'status' => $data['status'] ?? null,
            'amount' => $data['amount'] ?? null,
            'currency' => $data['currency'] ?? null,
            'reference' => $data['reference'] ?? $chargeId,
            'customer_email' => $data['customer']['email'] ?? null,
            'raw' => $data,
        ];
    }

    /**
     * Verifies an inbound webhook's signature before any payload is trusted.
     * Supports both the HMAC-SHA256 `flutterwave-signature` header (current
     * V4 docs) and the legacy direct-compare `verif-hash` header, since
     * publicly available V4 documentation was inconsistent about which one
     * ships in production — accepting either, verified correctly, is safe;
     * accepting neither breaks nothing that wasn't already broken.
     */
    public function verifyWebhookSignature(string $rawBody, ?string $signatureHeader, ?string $legacyHashHeader): bool
    {
        $secretHash = config('services.flutterwave.webhook_secret');

        if (blank($secretHash)) {
            Log::channel('flutterwave')->warning('Flutterwave webhook received but no webhook secret is configured — rejecting.');

            return false;
        }

        if (filled($signatureHeader)) {
            $expected = hash_hmac('sha256', $rawBody, $secretHash);

            return hash_equals($expected, $signatureHeader);
        }

        if (filled($legacyHashHeader)) {
            return hash_equals($secretHash, $legacyHashHeader);
        }

        return false;
    }

    /**
     * Sends an authenticated request to the Flutterwave V4 API, retrying
     * once with a freshly-forced token on a 401 (covers a token that
     * expired or was revoked mid-cache-life). Never logs the request body
     * (may contain customer PII) or any credential.
     */
    private function request(string $method, string $path, array $payload = []): ?array
    {
        $token = $this->getAccessToken();

        if (! $token) {
            return null;
        }

        $url = rtrim(config('services.flutterwave.base_url'), '/').'/'.ltrim($path, '/');

        $send = fn (string $bearer) => Http::withToken($bearer)->acceptJson()->timeout(15)->{$method}($url, $payload);

        try {
            $response = $send($token);

            if ($response->status() === 401) {
                $token = $this->getAccessToken(forceRefresh: true);

                if (! $token) {
                    return null;
                }

                $response = $send($token);
            }

            if (! $response->successful()) {
                // The status alone ("422") tells an admin nothing actionable — Flutterwave's
                // response body carries the actual reason (unverified merchant account,
                // disabled currency, invalid redirect_url domain, etc.), so log it too. This
                // is Flutterwave's own response to OUR request, not anything submitted by a
                // customer, so it's safe to log in full.
                Log::channel('flutterwave')->warning('Flutterwave API request failed', [
                    'path' => $path,
                    'status' => $response->status(),
                    'response' => $response->json() ?? $response->body(),
                ]);

                return null;
            }

            return $response->json();
        } catch (Throwable $e) {
            Log::channel('flutterwave')->error('Flutterwave API request threw an exception', ['path' => $path, 'message' => $e->getMessage()]);

            return null;
        }
    }
}
