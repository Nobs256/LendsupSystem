<?php

class Relworx
{
    /**
     * Generate a unique payment reference
     */
    public static function generateReference($tenant_id)
    {
        if ((int) $tenant_id <= 0) {
            throw new InvalidArgumentException('A valid tenant ID is required for Relworx payments.');
        }

        return SYSTEM_CODE . '_' . (int) $tenant_id . '_' . time() . '_' . mt_rand(1000,9999);
    }

    /**
     * Initiate Mobile Money Collection
     */
    public static function requestPayment($phone, $amount, $reference = null)
    {
        if (empty($reference)) {
            throw new InvalidArgumentException('A tenant-aware payment reference is required.');
        }

        $payload = json_encode([
            "account_no" => RELWORX_ACCOUNT_NO,
            "reference"  => $reference,
            "msisdn"     => $phone,
            "currency"   => "UGX",
            "amount"     => (float)$amount,
            "description"=> "SMS Purchase"
        ]);

        $ch = curl_init("https://payments.relworx.com/api/mobile-money/request-payment");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                "Accept: application/vnd.relworx.v2",
                "Content-Type: application/json",
                "Authorization: Bearer ".RELWORX_API_KEY
            ]
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return [
                'success' => false,
                'message' => curl_error($ch)
            ];
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}