<?php

namespace Tests\Unit;

use Tests\TestCase;

class BusinessAddressTest extends TestCase
{
    public function test_business_address_line_formats_wahroonga_address(): void
    {
        config([
            'riskwisdom.address' => [
                'line1' => '7B Benson Close',
                'suburb' => 'Wahroonga',
                'state' => 'NSW',
                'postcode' => '2076',
                'country' => 'AU',
            ],
        ]);

        $this->assertSame('7B Benson Close, Wahroonga NSW 2076', business_address_line());
    }

    public function test_business_address_schema_returns_postal_address(): void
    {
        config([
            'riskwisdom.address' => [
                'line1' => '7B Benson Close',
                'suburb' => 'Wahroonga',
                'state' => 'NSW',
                'postcode' => '2076',
                'country' => 'AU',
            ],
        ]);

        $this->assertSame([
            '@type' => 'PostalAddress',
            'streetAddress' => '7B Benson Close',
            'addressLocality' => 'Wahroonga',
            'addressRegion' => 'NSW',
            'postalCode' => '2076',
            'addressCountry' => 'AU',
        ], business_address_schema());
    }

    public function test_business_address_helpers_return_empty_when_incomplete(): void
    {
        config([
            'riskwisdom.address' => [
                'line1' => '',
                'suburb' => '',
            ],
        ]);

        $this->assertSame('', business_address_line());
        $this->assertNull(business_address_schema());
    }
}
