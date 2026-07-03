<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidLeadEmail implements ValidationRule
{
    /**
     * @var list<string>
     */
    private const BLOCKED_LOCAL_PARTS = [
        'test',
        'testing',
        'fake',
        'spam',
        'trash',
        'junk',
        'temp',
        'tmp',
        'demo',
        'sample',
        'example',
        'asdf',
        'asdfg',
        'asdfgh',
        'qwerty',
        'qwer',
        'zxcv',
        'zxcvb',
        'abc',
        'abcd',
        'xxx',
        'null',
        'undefined',
        'nobody',
        'noone',
        'noreply',
        'donotreply',
        'do-not-reply',
        'user',
        'email',
        'mail',
        'admin',
        'root',
        'guest',
        'anonymous',
        'none',
        'na',
        'n/a',
        'foo',
        'bar',
        'baz',
        'sdfds',
        'sdf',
        'fds',
        'aaa',
        'bbb',
        'ccc',
    ];

    /**
     * @var list<string>
     */
    private const BLOCKED_DOMAINS = [
        'example.com',
        'example.org',
        'example.net',
        'test.com',
        'test.test',
        'invalid.com',
        'mailinator.com',
        'guerrillamail.com',
        'guerrillamail.net',
        'guerrillamail.org',
        'sharklasers.com',
        'yopmail.com',
        'tempmail.com',
        'throwaway.email',
        'maildrop.cc',
        'getnada.com',
        'trashmail.com',
        '10minutemail.com',
        'temp-mail.org',
        'fakeinbox.com',
        'discard.email',
        'dispostable.com',
        'mailnesia.com',
        'mintemail.com',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = strtolower(trim((string) $value));

        if ($email === '' || ! str_contains($email, '@')) {
            return;
        }

        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');

        $local = (string) $local;
        $domain = (string) $domain;

        if ($local === '' || $domain === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        if ($this->isBlockedLocalPart($local)) {
            $fail('Please enter a real email address you use regularly.');

            return;
        }

        if ($this->isBlockedDomain($domain)) {
            $fail('Please use a real email address, not a disposable or test address.');

            return;
        }

        if ($this->looksLikeGibberish($local)) {
            $fail('Please enter a real email address you use regularly.');

            return;
        }
    }

    private function isBlockedLocalPart(string $local): bool
    {
        $base = explode('+', $local, 2)[0];
        $normalized = str_replace(['.', '-', '_'], '', $base);

        if (in_array($base, self::BLOCKED_LOCAL_PARTS, true)) {
            return true;
        }

        if (str_starts_with($normalized, 'noreply') || str_contains($normalized, 'noreply')) {
            return true;
        }

        return (bool) preg_match('/^(test|fake|spam|temp|trash|junk|demo|sample|example)(\d+)?$/', $base);
    }

    private function isBlockedDomain(string $domain): bool
    {
        if (in_array($domain, $this->blockedDomains(), true)) {
            return true;
        }

        foreach ($this->blockedDomains() as $blocked) {
            if (str_ends_with($domain, '.'.$blocked)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<string>
     */
    private function blockedDomains(): array
    {
        if (app()->environment('production')) {
            return self::BLOCKED_DOMAINS;
        }

        $allowInNonProduction = [
            'example.com',
            'example.org',
            'example.net',
            'test.com',
            'test.test',
            'invalid.com',
        ];

        return array_values(array_filter(
            self::BLOCKED_DOMAINS,
            static fn (string $domain): bool => ! in_array($domain, $allowInNonProduction, true)
        ));
    }

    private function looksLikeGibberish(string $local): bool
    {
        $base = explode('+', $local, 2)[0];

        if (strlen($base) <= 2) {
            return true;
        }

        if (preg_match('/^(.)\1{3,}$/', $base)) {
            return true;
        }

        if (preg_match('/^\d+$/', $base)) {
            return true;
        }

        if (preg_match('/^[bcdfghjklmnpqrstvwxyz]{5,}$/i', $base)) {
            return true;
        }

        return false;
    }
}
