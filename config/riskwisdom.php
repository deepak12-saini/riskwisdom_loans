<?php

return [
    'phone' => '+61 421 670 636',
    'phone_tel' => '+61421670636',
    'email' => 'info@riskwisdomloans.com.au',
    'legal_name' => 'Risk Wisdom Loans Pty Ltd',
    'brand_name' => 'Riskwisdom Loans',

    'calendly_url' => env('CALENDLY_URL', 'https://calendly.com/riskwisdomloans-info/30min'),
    'calendly_hide_branding' => env('CALENDLY_HIDE_BRANDING', true),

    'google_tag_manager_id' => env('GOOGLE_TAG_MANAGER_ID'),
    'google_analytics_id' => env('GOOGLE_ANALYTICS_ID'),
    'microsoft_clarity_id' => env('MICROSOFT_CLARITY_ID'),

    'lead_types' => [
        'contact' => 'Contact form',
        'borrowing_power' => 'Borrowing power calculator',
        'rate_review' => 'Rate review',
    ],

    'rate_review' => [
        'callback_promise' => 'Fast callback — we aim to call you within 2 business hours.',
    ],

    'ad_landing_pages' => [
        'refinance' => 'pages.refinance',
        'refinance_rates' => 'pages.refinance-rates',
        'refinance_calculator' => 'pages.refinance-calculator',
        'refinance_cashback' => 'pages.refinance-cashback',
        'home_loans' => 'pages.home-loans',
        'first_home_buyer' => 'pages.first-home-buyer',
        'borrowing_power' => 'tools.borrowing-power',
        'repayment_calculator' => 'tools.repayment-calculator',
        'stamp_duty' => 'tools.stamp-duty',
        'rate_review' => 'rate-review',
        'book' => 'book',
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

    'client_statuses' => [
        'active' => 'Active',
        'archived' => 'Archived',
    ],

    'task_statuses' => [
        'open' => 'Open',
        'in_progress' => 'In progress',
        'done' => 'Done',
    ],

    'task_owners' => [
        'client' => 'Client',
        'broker' => 'Broker',
    ],

    'task_priorities' => [
        'low' => 'Low',
        'normal' => 'Normal',
        'high' => 'High',
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
