<?php

namespace Tests\Unit;

use Tests\TestCase;

class CalendlyPrefillUrlTest extends TestCase
{
    public function test_calendly_prefill_url_includes_invitee_details(): void
    {
        config([
            'riskwisdom.calendly_url' => 'https://calendly.com/riskwisdomloans-info/30min',
        ]);

        $url = calendly_prefill_url('Jane Smith', 'jane@example.com', '+61400000000', 'Jane', 'Smith');

        $this->assertNotNull($url);
        $this->assertStringStartsWith('https://calendly.com/riskwisdomloans-info/30min?', $url);
        $this->assertStringContainsString('name=Jane+Smith', $url);
        $this->assertStringContainsString('email=jane%40example.com', $url);
        $this->assertStringContainsString('first_name=Jane', $url);
        $this->assertStringContainsString('last_name=Smith', $url);
        $this->assertStringContainsString('a1=%2B61400000000', $url);
    }

    public function test_calendly_prefill_url_returns_null_when_not_configured(): void
    {
        config(['riskwisdom.calendly_url' => '']);

        $this->assertNull(calendly_prefill_url('Jane', 'jane@example.com'));
    }
}
