<?php

namespace App\Services\Crm;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class ZohoCrmAdapter
{
    private const ACCOUNTS_URL = 'https://accounts.zoho.com';

    private string $apiDomain;

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $redirectUri,
        private readonly ?string $accessToken = null,
        ?string $apiDomain = null,
    ) {
        $this->apiDomain = $apiDomain ?: 'https://www.zohoapis.com';
    }

    private function baseUrl(): string
    {
        return "{$this->apiDomain}/crm/v3";
    }

    private function request(string $method, string $path, ?array $body = null): array
    {
        $pending = Http::withHeaders(['Authorization' => "Zoho-oauthtoken {$this->accessToken}"]);
        $verb = strtolower($method);

        $response = $verb === 'get'
            ? $pending->get($this->baseUrl().$path)
            : $pending->{$verb}($this->baseUrl().$path, $body ?? []);

        if (! $response->successful()) {
            throw new RuntimeException("Zoho API {$method} {$path} → {$response->status()}: {$response->body()}");
        }

        return $response->json() ?? [];
    }

    public function getAuthorizationUrl(string $state = ''): string
    {
        $params = http_build_query([
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'scope' => 'ZohoCRM.modules.ALL,ZohoCRM.settings.ALL,ZohoCRM.org.READ',
            'redirect_uri' => $this->redirectUri,
            'access_type' => 'offline',
            'state' => $state,
        ]);

        return self::ACCOUNTS_URL."/oauth/v2/auth?{$params}";
    }

    public function exchangeCodeForTokens(string $code): array
    {
        $response = Http::asForm()->post(self::ACCOUNTS_URL.'/oauth/v2/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'code' => $code,
        ]);

        $data = $response->json() ?? [];

        if (isset($data['error'])) {
            throw new RuntimeException("Zoho OAuth error: {$data['error']}");
        }

        return [
            'access_token' => $data['access_token'] ?? null,
            'refresh_token' => $data['refresh_token'] ?? null,
            'expires_in' => $data['expires_in'] ?? null,
            'api_domain' => $data['api_domain'] ?? null,
            'token_type' => $data['token_type'] ?? null,
        ];
    }

    public function refreshAccessToken(string $refreshToken): array
    {
        $response = Http::asForm()->post(self::ACCOUNTS_URL.'/oauth/v2/token', [
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken,
        ]);

        $data = $response->json() ?? [];

        if (isset($data['error'])) {
            throw new RuntimeException("Zoho token refresh error: {$data['error']}");
        }

        return ['access_token' => $data['access_token'] ?? null, 'expires_in' => $data['expires_in'] ?? null];
    }

    public function testConnection(): array
    {
        try {
            $data = $this->request('GET', '/org');

            return ['success' => true, 'message' => 'Connected to Zoho CRM successfully', 'details' => $data];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ── Contacts ──────────────────────────────────────

    public function createContact(array $contact): string
    {
        $data = $this->request('POST', '/Contacts', [
            'data' => [[
                'First_Name' => $contact['first_name'] ?? null,
                'Last_Name' => $contact['last_name'] ?? null,
                'Email' => $contact['email'] ?? null,
                'Phone' => $contact['phone'] ?? null,
                'Mailing_Street' => $contact['address'] ?? null,
                'Mailing_City' => $contact['city'] ?? null,
                'Mailing_State' => $contact['state'] ?? null,
                'Mailing_Country' => $contact['country'] ?? null,
            ]],
        ]);

        return $data['data'][0]['details']['id'];
    }

    public function updateContact(string $id, array $contact): void
    {
        $this->request('PUT', "/Contacts/{$id}", [
            'data' => [[
                'First_Name' => $contact['first_name'] ?? null,
                'Last_Name' => $contact['last_name'] ?? null,
                'Phone' => $contact['phone'] ?? null,
            ]],
        ]);
    }

    public function searchContacts(string $email): array
    {
        try {
            $data = $this->request('GET', '/Contacts/search?email='.urlencode($email));

            return array_map(fn ($r) => ['id' => $r['id'], 'email' => $r['Email'] ?? null], $data['data'] ?? []);
        } catch (\Throwable) {
            return [];
        }
    }

    // ── Accounts ──────────────────────────────────────

    public function createAccount(array $account): string
    {
        $data = $this->request('POST', '/Accounts', [
            'data' => [[
                'Account_Name' => $account['name'] ?? null,
                'Account_Type' => $account['type'] ?? null,
                'Phone' => $account['phone'] ?? null,
                'Email' => $account['email'] ?? null,
                'Billing_Street' => $account['address'] ?? null,
                'Billing_City' => $account['city'] ?? null,
                'Billing_State' => $account['state'] ?? null,
                'Billing_Country' => $account['country'] ?? null,
                'Industry' => $account['industry'] ?? null,
            ]],
        ]);

        return $data['data'][0]['details']['id'];
    }

    public function updateAccount(string $id, array $account): void
    {
        $this->request('PUT', "/Accounts/{$id}", [
            'data' => [['Account_Name' => $account['name'] ?? null, 'Phone' => $account['phone'] ?? null]],
        ]);
    }

    public function searchAccounts(string $name): array
    {
        try {
            $data = $this->request('GET', '/Accounts/search?word='.urlencode($name));

            return array_map(fn ($r) => ['id' => $r['id'], 'name' => $r['Account_Name'] ?? null], $data['data'] ?? []);
        } catch (\Throwable) {
            return [];
        }
    }

    // ── Deals ──────────────────────────────────────────

    public function createDeal(array $deal): string
    {
        $data = $this->request('POST', '/Deals', [
            'data' => [[
                'Deal_Name' => $deal['name'] ?? null,
                'Stage' => $deal['stage'] ?? 'Qualification',
                'Amount' => $deal['amount'] ?? null,
                'Currency' => $deal['currency'] ?? null,
                'Closing_Date' => $deal['close_date'] ?? null,
            ]],
        ]);

        return $data['data'][0]['details']['id'];
    }

    // ── Leads ──────────────────────────────────────────

    public function createLead(array $lead): string
    {
        $data = $this->request('POST', '/Leads', [
            'data' => [[
                'First_Name' => $lead['first_name'] ?? null,
                'Last_Name' => $lead['last_name'] ?? null,
                'Email' => $lead['email'] ?? null,
                'Phone' => $lead['phone'] ?? null,
                'Company' => $lead['company'] ?? null,
                'Lead_Source' => $lead['lead_source'] ?? 'Web Site',
                'Lead_Status' => $lead['status'] ?? 'Not Contacted',
                'Annual_Revenue' => $lead['amount'] ?? null,
            ]],
        ]);

        return $data['data'][0]['details']['id'];
    }

    // ── Tickets (via Cases module) ─────────────────────

    public function createTicket(array $ticket): string
    {
        $data = $this->request('POST', '/Cases', [
            'data' => [[
                'Subject' => $ticket['subject'] ?? null,
                'Description' => $ticket['description'] ?? null,
                'Status' => $ticket['status'] ?? 'New',
                'Priority' => $ticket['priority'] ?? 'Medium',
            ]],
        ]);

        return $data['data'][0]['details']['id'];
    }
}
