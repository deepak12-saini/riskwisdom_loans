<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidLeadPhoneNumber implements DataAwareRule, ValidationRule
{
    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = trim((string) $value);

        if ($phone === '') {
            return;
        }

        $countryCode = (string) ($this->data['phone_country_code'] ?? '+61');
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            $fail('Please enter a valid phone number.');

            return;
        }

        $codeDigits = ltrim($countryCode, '+');

        if (str_starts_with($digits, $codeDigits) && strlen($digits) > strlen($codeDigits) + 5) {
            $digits = substr($digits, strlen($codeDigits));
        }

        $valid = match ($countryCode) {
            '+61' => (bool) preg_match('/^0?(?:4\d{8}|[2378]\d{8})$/', $digits),
            '+64' => (bool) preg_match('/^0?\d{8,10}$/', $digits),
            '+91' => (bool) preg_match('/^[6-9]\d{9}$/', $digits),
            '+44' => (bool) preg_match('/^0?\d{9,10}$/', $digits),
            '+1' => (bool) preg_match('/^\d{10}$/', $digits),
            '+65' => (bool) preg_match('/^[3689]\d{7}$/', $digits),
            '+971' => (bool) preg_match('/^0?\d{8,9}$/', $digits),
            default => strlen($digits) >= 6 && strlen($digits) <= 15,
        };

        if (! $valid) {
            $fail($this->messageFor($countryCode));
        }
    }

    private function messageFor(string $countryCode): string
    {
        return match ($countryCode) {
            '+61' => 'Please enter a valid Australian phone number (mobile or landline).',
            '+91' => 'Please enter a valid Indian mobile number (10 digits).',
            '+64' => 'Please enter a valid New Zealand phone number.',
            '+44' => 'Please enter a valid UK phone number.',
            '+1' => 'Please enter a valid US/Canada phone number (10 digits).',
            '+65' => 'Please enter a valid Singapore phone number.',
            '+971' => 'Please enter a valid UAE phone number.',
            default => 'Please enter a valid phone number for the selected country.',
        };
    }
}
