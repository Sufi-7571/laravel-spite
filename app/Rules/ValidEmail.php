<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ValidEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Basic format validation
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('The email address format is invalid.');
            return;
        }

        // Call AbstractAPI Email Reputation to verify email
        try {
            $apiKey = config('services.abstractapi.key');
            
            if (!$apiKey) {
                Log::error('AbstractAPI key not configured');
                $fail('Email verification service is not configured.');
                return;
            }

            $url = 'https://emailreputation.abstractapi.com/v1/?api_key=' . $apiKey . '&email=' . urlencode($value);

            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Email verification response', $data);

                // Check email deliverability
                if (isset($data['email_deliverability'])) {
                    $deliverability = $data['email_deliverability'];

                    // Check if format is valid
                    if (isset($deliverability['is_format_valid']) && !$deliverability['is_format_valid']) {
                        $fail('The email address format is invalid.');
                        return;
                    }

                    // Check if MX records are valid
                    if (isset($deliverability['is_mx_valid']) && !$deliverability['is_mx_valid']) {
                        $fail('The email domain does not have valid mail servers.');
                        return;
                    }

                    // Check if SMTP is valid (email actually exists)
                    if (isset($deliverability['is_smtp_valid']) && !$deliverability['is_smtp_valid']) {
                        $fail('The email address does not exist or cannot receive emails.');
                        return;
                    }

                    // Check deliverability status
                    if (isset($deliverability['status']) && in_array($deliverability['status'], ['undeliverable', 'risky', 'unknown'])) {
                        $fail('The email address is ' . $deliverability['status'] . ' and cannot be used.');
                        return;
                    }
                }

                // Check if it's a disposable email
                if (isset($data['email_quality']['is_disposable']) && $data['email_quality']['is_disposable']) {
                    $fail('Disposable or temporary email addresses are not allowed.');
                    return;
                }

                // Check email quality score (0.0 - 1.0, lower is worse)
                if (isset($data['email_quality']['score']) && $data['email_quality']['score'] < 0.3) {
                    $fail('This email address has a poor quality score and cannot be used.');
                    return;
                }

                // Check risk status
                if (isset($data['email_risk']['address_risk_status']) && $data['email_risk']['address_risk_status'] === 'high') {
                    $fail('This email address is high-risk and cannot be used.');
                    return;
                }

            } else {
                Log::error('Email verification API failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                $fail('Unable to verify email address. Please try again later.');
            }

        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());
            $fail('Unable to verify email address. Please try again later.');
        }
    }
}