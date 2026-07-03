<?php

namespace Tests\Unit;

use App\Rules\ValidAustralianPhone;
use App\Rules\ValidLeadMessage;
use App\Rules\ValidLeadName;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LeadSpamRulesTest extends TestCase
{
    #[DataProvider('validAustralianPhones')]
    public function test_accepts_valid_australian_phone_numbers(string $phone): void
    {
        $this->assertRulePasses(new ValidAustralianPhone, $phone);
    }

    #[DataProvider('invalidAustralianPhones')]
    public function test_rejects_invalid_australian_phone_numbers(string $phone): void
    {
        $this->assertRuleFails(new ValidAustralianPhone, $phone);
    }

    #[DataProvider('spamMessages')]
    public function test_rejects_spam_messages(string $message): void
    {
        $this->assertRuleFails(new ValidLeadMessage, $message);
    }

    public function test_accepts_genuine_enquiry_message(): void
    {
        $this->assertRulePasses(
            new ValidLeadMessage,
            'I would like help refinancing my home loan in Tasmania.'
        );
    }

    public function test_rejects_identical_first_and_last_name_check_via_helper(): void
    {
        $validator = validator([
            'first_name' => 'DavidrekDS',
            'last_name' => 'DavidrekDS',
        ], [
            'first_name' => lead_name_rules(),
            'last_name' => lead_name_rules(),
        ]);

        apply_lead_identity_checks($validator);

        $this->assertTrue($validator->fails());
    }

    #[DataProvider('validNames')]
    public function test_accepts_real_names(string $name): void
    {
        $this->assertRulePasses(new ValidLeadName, $name);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function validAustralianPhones(): array
    {
        return [
            'mobile spaced' => ['0412 345 678'],
            'mobile plain' => ['0412345678'],
            'mobile international' => ['+61412345678'],
            'sydney landline' => ['02 9123 4567'],
            'brisbane landline' => ['07 3123 4567'],
        ];
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function invalidAustralianPhones(): array
    {
        return [
            'foreign long number' => ['89419874553'],
            'too short' => ['0400123'],
            'invalid prefix' => ['0512345678'],
        ];
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function spamMessages(): array
    {
        return [
            'telegram link' => ['Contact us on Telegram - https://t.me/FeedbackFormEU'],
            'whatsapp link' => ['WhatsApp https://wa.me/+375259112693'],
            'website owners pitch' => ['Our platform helps companies introduce their offers to website owners.'],
            'noticed your website' => ['I noticed your website while browsing the internet.'],
        ];
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function validNames(): array
    {
        return [
            'simple' => ['Kal'],
            'hyphenated' => ['Mary-Jane'],
            'apostrophe' => ["O'Brien"],
        ];
    }

    private function assertRulePasses(object $rule, string $value): void
    {
        $failed = false;

        $rule->validate('field', $value, function () use (&$failed): void {
            $failed = true;
        });

        $this->assertFalse($failed);
    }

    private function assertRuleFails(object $rule, string $value): void
    {
        $failed = false;

        $rule->validate('field', $value, function () use (&$failed): void {
            $failed = true;
        });

        $this->assertTrue($failed);
    }
}
