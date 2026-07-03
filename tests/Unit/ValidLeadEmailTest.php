<?php

namespace Tests\Unit;

use App\Rules\ValidLeadEmail;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ValidLeadEmailTest extends TestCase
{
    #[DataProvider('invalidEmails')]
    public function test_rejects_fake_or_spam_emails(string $email): void
    {
        $failed = false;

        (new ValidLeadEmail)->validate('email', $email, function () use (&$failed): void {
            $failed = true;
        });

        $this->assertTrue($failed, "Expected [{$email}] to be rejected.");
    }

    #[DataProvider('validEmails')]
    public function test_accepts_real_looking_emails(string $email): void
    {
        $failed = false;

        (new ValidLeadEmail)->validate('email', $email, function () use (&$failed): void {
            $failed = true;
        });

        $this->assertFalse($failed, "Expected [{$email}] to be accepted.");
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function invalidEmails(): array
    {
        return [
            'test gmail' => ['test@gmail.com'],
            'fake address' => ['fake@outlook.com'],
            'disposable domain' => ['user@mailinator.com'],
            'gibberish local' => ['sdfds@gmail.com'],
            'numeric local' => ['12345@gmail.com'],
            'no reply dotted' => ['no.reply.JoanAndersen@gmail.com'],
        ];
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function validEmails(): array
    {
        return [
            'normal gmail' => ['deepaksaini10036@gmail.com'],
            'business email' => ['kal@riskwisdomloans.com.au'],
            'name with dots' => ['jane.borrower@example.com'],
        ];
    }
}
