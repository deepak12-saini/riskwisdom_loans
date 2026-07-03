<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidLeadName implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $name = trim((string) $value);

        if ($name === '') {
            return;
        }

        if (! preg_match("/^[\p{L}\s'\-.]+$/u", $name)) {
            $fail('Please enter a real name using letters only.');

            return;
        }

        if (preg_match('/\d/', $name)) {
            $fail('Please enter a real name using letters only.');

            return;
        }

        $lettersOnly = preg_replace('/[^\p{L}]/u', '', $name) ?? '';

        if (strlen($lettersOnly) < 2) {
            $fail('Please enter your full name.');

            return;
        }

        if (preg_match('/^(.)\1{3,}$/u', $lettersOnly)) {
            $fail('Please enter a real name.');

            return;
        }
    }
}
