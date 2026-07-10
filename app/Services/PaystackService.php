<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaystackService
{
    public function verifyTransaction(string $reference): array
    {
        $response = Http::withToken(config('services.paystack.secret_key'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        $data = $response->json();

        $successful = $response->ok()
            && ($data['status'] ?? false) === true
            && ($data['data']['status'] ?? null) === 'success';

        return [
            'successful' => $successful,
            'amount' => isset($data['data']['amount']) ? $data['data']['amount'] / 100 : null,
            'currency' => $data['data']['currency'] ?? null,
            'reference' => $data['data']['reference'] ?? $reference,
            'raw' => $data,
        ];
    }
}
