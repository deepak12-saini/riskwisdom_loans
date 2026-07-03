<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidLeadMessage implements ValidationRule
{
    /**
     * @var list<string>
     */
    private const BLOCKED_PATTERNS = [
        '/\bt\.me\//i',
        '/\bwa\.me\//i',
        '/\btelegram\b/i',
        '/\bwhatsapp\b/i',
        '/\bviber\b/i',
        '/\bskype\b/i',
        '/https?:\/\//i',
        '/\bwww\./i',
        '/\bwebsite owners\b/i',
        '/\bintroduce their offers\b/i',
        '/\bnoticed your website\b/i',
        '/\bseo\b/i',
        '/\bbacklink/i',
        '/\bguest post/i',
        '/\bcontact us\.\s*telegram/i',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $message = trim((string) $value);

        if ($message === '') {
            return;
        }

        foreach (self::BLOCKED_PATTERNS as $pattern) {
            if (preg_match($pattern, $message)) {
                $fail('Please tell us about your finance goals in plain text, without links or promotional messages.');

                return;
            }
        }
    }
}
