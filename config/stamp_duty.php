<?php

/**
 * Simplified Australian transfer duty brackets for guide estimates only.
 * Rates are approximate — confirm with your state revenue office or broker.
 */
return [
    'mortgage_registration_fee' => 187,

    'states' => [
        'NSW' => [
            'label' => 'New South Wales',
            'brackets' => [
                ['max' => 17000, 'rate' => 0.0125],
                ['max' => 37000, 'rate' => 0.015],
                ['max' => 99000, 'rate' => 0.0175],
                ['max' => 372000, 'rate' => 0.035],
                ['max' => 1240000, 'rate' => 0.045],
                ['max' => null, 'rate' => 0.055],
            ],
            'fhb' => [
                'full_exemption_up_to' => 800000,
                'partial_exemption_up_to' => 1000000,
            ],
        ],
        'VIC' => [
            'label' => 'Victoria',
            'brackets' => [
                ['max' => 25000, 'rate' => 0.014],
                ['max' => 130000, 'rate' => 0.024],
                ['max' => 960000, 'rate' => 0.06],
                ['max' => null, 'rate' => 0.055],
            ],
            'fhb' => [
                'full_exemption_up_to' => 600000,
                'partial_exemption_up_to' => 750000,
            ],
        ],
        'QLD' => [
            'label' => 'Queensland',
            'brackets' => [
                ['max' => 5000, 'rate' => 0],
                ['max' => 75000, 'rate' => 0.015],
                ['max' => 540000, 'rate' => 0.035],
                ['max' => 1000000, 'rate' => 0.045],
                ['max' => null, 'rate' => 0.0575],
            ],
            'fhb' => [
                'full_exemption_up_to' => 500000,
                'partial_exemption_up_to' => 550000,
            ],
        ],
        'WA' => [
            'label' => 'Western Australia',
            'brackets' => [
                ['max' => 120000, 'rate' => 0.019],
                ['max' => 150000, 'rate' => 0.0285],
                ['max' => 360000, 'rate' => 0.038],
                ['max' => 725000, 'rate' => 0.0475],
                ['max' => null, 'rate' => 0.0515],
            ],
            'fhb' => [
                'full_exemption_up_to' => 430000,
                'partial_exemption_up_to' => 530000,
            ],
        ],
        'SA' => [
            'label' => 'South Australia',
            'brackets' => [
                ['max' => 12000, 'rate' => 0.01],
                ['max' => 30000, 'rate' => 0.02],
                ['max' => 50000, 'rate' => 0.03],
                ['max' => 100000, 'rate' => 0.035],
                ['max' => 200000, 'rate' => 0.04],
                ['max' => 250000, 'rate' => 0.045],
                ['max' => 300000, 'rate' => 0.0475],
                ['max' => 500000, 'rate' => 0.05],
                ['max' => null, 'rate' => 0.055],
            ],
            'fhb' => [
                'full_exemption_up_to' => 650000,
                'partial_exemption_up_to' => 700000,
            ],
        ],
        'TAS' => [
            'label' => 'Tasmania',
            'brackets' => [
                ['max' => 3000, 'rate' => 0],
                ['max' => 25000, 'rate' => 0.0175],
                ['max' => 75000, 'rate' => 0.0225],
                ['max' => 200000, 'rate' => 0.035],
                ['max' => 375000, 'rate' => 0.04],
                ['max' => 725000, 'rate' => 0.045],
                ['max' => null, 'rate' => 0.045],
            ],
            'fhb' => [
                'full_exemption_up_to' => 600000,
                'partial_exemption_up_to' => 750000,
            ],
        ],
        'ACT' => [
            'label' => 'Australian Capital Territory',
            'brackets' => [
                ['max' => 200000, 'rate' => 0.006],
                ['max' => 300000, 'rate' => 0.022],
                ['max' => 500000, 'rate' => 0.034],
                ['max' => 750000, 'rate' => 0.052],
                ['max' => 1000000, 'rate' => 0.059],
                ['max' => 1455000, 'rate' => 0.064],
                ['max' => null, 'rate' => 0.0454],
            ],
            'fhb' => [
                'full_exemption_up_to' => 1000000,
                'partial_exemption_up_to' => 1000000,
            ],
        ],
        'NT' => [
            'label' => 'Northern Territory',
            'brackets' => [
                ['max' => 525000, 'rate' => 0.0458],
                ['max' => 3000000, 'rate' => 0.0495],
                ['max' => null, 'rate' => 0.0575],
            ],
            'fhb' => [
                'full_exemption_up_to' => 650000,
                'partial_exemption_up_to' => 650000,
            ],
        ],
    ],
];
