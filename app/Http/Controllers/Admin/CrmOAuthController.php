<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmConnection;
use App\Services\Crm\ZohoCrmAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CrmOAuthController extends Controller
{
    public function authorize(): RedirectResponse
    {
        $connection = CrmConnection::where('is_active', true)->where('provider', 'zoho')->first();

        if (! $connection?->client_id || ! $connection->client_secret || ! $connection->redirect_uri) {
            return redirect('/admin/integrations/crm')->with('error', 'Zoho credentials not configured');
        }

        $adapter = new ZohoCrmAdapter(
            clientId: $connection->client_id,
            clientSecret: $connection->client_secret,
            redirectUri: $connection->redirect_uri,
        );

        return redirect()->away($adapter->getAuthorizationUrl((string) $connection->id));
    }

    public function callback(Request $request): RedirectResponse
    {
        $code = $request->query('code');
        $state = $request->query('state');
        $error = $request->query('error');

        if ($error) {
            return redirect('/admin/integrations/crm?error='.urlencode($error));
        }

        if (! $code || ! $state) {
            return redirect('/admin/integrations/crm?error=missing_params');
        }

        $connection = CrmConnection::find($state);

        if (! $connection?->client_id || ! $connection->client_secret || ! $connection->redirect_uri) {
            return redirect('/admin/integrations/crm?error=invalid_connection');
        }

        try {
            $adapter = new ZohoCrmAdapter(
                clientId: $connection->client_id,
                clientSecret: $connection->client_secret,
                redirectUri: $connection->redirect_uri,
            );

            $tokens = $adapter->exchangeCodeForTokens($code);

            $connection->update([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'] ?? $connection->refresh_token,
                'token_expires_at' => $tokens['expires_in'] ? now()->addSeconds($tokens['expires_in']) : null,
                'api_domain' => $tokens['api_domain'] ?? $connection->api_domain,
                'is_connected' => true,
                'last_error' => null,
                'health_score' => 100,
            ]);

            return redirect('/admin/integrations/crm?connected=true');
        } catch (\Throwable $e) {
            return redirect('/admin/integrations/crm?error='.urlencode($e->getMessage()));
        }
    }
}
