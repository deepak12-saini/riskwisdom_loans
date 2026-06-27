<?php

namespace Tests\Unit;

use App\Services\StampDutyCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StampDutyCalculatorTest extends TestCase
{
    private StampDutyCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new StampDutyCalculator;
    }

    public function test_it_returns_zero_duty_for_zero_property_value(): void
    {
        $result = $this->calculator->estimate('NSW', 0);

        $this->assertSame(0.0, $result['duty']);
        $this->assertSame(187.0, $result['total_government_charges']);
    }

    public function test_first_home_buyer_can_reduce_nsw_duty(): void
    {
        $standard = $this->calculator->estimate('NSW', 700000, false);
        $fhb = $this->calculator->estimate('NSW', 700000, true);

        $this->assertGreaterThan($fhb['duty'], $standard['duty']);
        $this->assertNotNull($fhb['fhb_note']);
    }

    #[DataProvider('supportedStatesProvider')]
    public function test_it_supports_all_configured_states(string $state): void
    {
        $result = $this->calculator->estimate($state, 500000);

        $this->assertSame($state, $result['state']);
        $this->assertGreaterThan(0, $result['duty']);
    }

    public static function supportedStatesProvider(): array
    {
        return [
            ['NSW'],
            ['VIC'],
            ['QLD'],
            ['WA'],
            ['SA'],
            ['TAS'],
            ['ACT'],
            ['NT'],
        ];
    }
}
