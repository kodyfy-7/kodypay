<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BankService 
{
    public function list() {
        $secret_key = env('PAYSTACK_SECRET_KEY');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$secret_key,
        ])->get('https://api.paystack.co/bank');

        // Access the response body
        $responseBody = $response->json();
        return $responseBody;
    }

    public function addRecipient($data) {
        $secret_key = env('PAYSTACK_SECRET_KEY');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$secret_key,
            'Content-Type' => 'application/json',
        ])->post('https://api.paystack.co/transferrecipient', [
            'type' => $data['type'],
            'name' => $data['account_name'],
            'account_number' => $data['account_number'],
            'bank_code' => $data['bank_code'],
            'currency' => 'NGN'
        ]);
        return $response;
    }

    public function initiateTrf($data) {
        $secret_key = env('PAYSTACK_SECRET_KEY');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$secret_key,
            'Content-Type' => 'application/json',
        ])->post('https://api.paystack.co/transfer', [
            'source' => 'balance',
            'reason' => 'Calm down',
            'amount' => $data['amount'],
            'recipient' => $data['recipient_code'],
        ]);
        return $response;
    }

    public function finalizeTrf($data) {
        $secret_key = env('PAYSTACK_SECRET_KEY');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$secret_key,
            'Content-Type' => 'application/json',
        ])->post('https://api.paystack.co/transfer', [
            'transfer_code' => 'TRF_vsyqdmlzble3uii',
            'otp' => '928783',
        ]);
        return $response;
    }
}