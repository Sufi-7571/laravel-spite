<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.recaptcha.secret_key');

        if (!$secretKey) {
            Log::error('reCAPTCHA secret key not configured');
            $fail('reCAPTCHA is not properly configured.');
            return;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip()
            ]);

            if ($response->successful()) {
                $result = $response->json();

                if (!isset($result['success']) || !$result['success']) {
                    $fail('Please complete the reCAPTCHA verification.');
                    return;
                }
            } else {
                Log::error('reCAPTCHA verification failed', ['response' => $response->body()]);
                $fail('reCAPTCHA verification failed. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('reCAPTCHA error: ' . $e->getMessage());
            $fail('reCAPTCHA verification failed. Please try again.');
        }
    }
}