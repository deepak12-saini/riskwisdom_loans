<?php

return [
    'phone' => '+61 421 670 636',
    'phone_tel' => '+61421670636',
    'email' => 'info@riskwisdomloans.com.au',
    'legal_name' => 'Risk Wisdom Loans Pty Ltd',
    'brand_name' => 'Riskwisdom Loans',

    'calendly_url' => env('CALENDLY_URL', 'https://calendly.com/riskwisdomloans-info/30min'),

    'lead_types' => [
        'contact' => 'Contact form',
        'borrowing_power' => 'Borrowing power calculator',
    ],

    'loan_types' => [
        'home_purchase' => 'Home purchase',
        'refinance' => 'Refinance',
        'investment' => 'Investment property',
        'commercial' => 'Commercial',
        'other' => 'Other',
    ],

    'timelines' => [
        'ready_now' => 'Ready now',
        '1_3_months' => '1–3 months',
        '3_6_months' => '3–6 months',
        'researching' => 'Just researching',
    ],

    'states' => [
        'NSW' => 'NSW',
        'VIC' => 'VIC',
        'QLD' => 'QLD',
        'WA' => 'WA',
        'SA' => 'SA',
        'TAS' => 'TAS',
        'ACT' => 'ACT',
        'NT' => 'NT',
    ],

    'intent_map' => [
        'first_home_buyer' => 'home_purchase',
        'families' => 'refinance',
        'investors' => 'investment',
        'professionals' => 'home_purchase',
        'business_owners' => 'commercial',
        'over_50s' => 'refinance',
        'refinance' => 'refinance',
        'home_loans' => 'home_purchase',
        'investment' => 'investment',
        'commercial' => 'commercial',
    ],
];
