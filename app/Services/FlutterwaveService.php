<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FlutterwaveService
{
    public function verifyTransaction(string $transactionId): array
    {
        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

        $data = $response->json();

        $successful = $response->ok()
            && ($data['status'] ?? null) === 'success'
            && ($data['data']['status'] ?? null) === 'successful';

        return [
            'successful' => $successful,
            'amount' => $data['data']['amount'] ?? null,
            'currency' => $data['data']['currency'] ?? null,
            'reference' => $data['data']['tx_ref'] ?? $transactionId,
            'raw' => $data,
        ];
    }
}
