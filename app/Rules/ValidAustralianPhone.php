<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAustralianPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = trim((string) $value);

        if ($phone === '') {
            return;
        }

        $normalized = preg_replace('/[\s\-().]/', '', $phone) ?? $phone;

        if (str_starts_with($normalized, '+61')) {
            $normalized = '0'.substr($normalized, 3);
        } elseif (str_starts_with($normalized, '61') && strlen($normalized) === 11) {
            $normalized = '0'.substr($normalized, 2);
        }

        if (! preg_match('/^0(?:4\d{8}|[2378]\d{8})$/', $normalized)) {
            $fail('Please enter a valid Australian phone number (mobile or landline).');
        }
    }
}
