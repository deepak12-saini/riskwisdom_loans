<?php

return [
    'phone' => '+61 421 670 636',
    'phone_tel' => '+61421670636',
    'email' => 'info@riskwisdomloans.com.au',
    'contact_to_address' => env('CONTACT_TO_ADDRESS', 'info@riskwisdomloans.com.au'),
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
        'guide_download' => 'Guide download',
        'chat_widget' => 'After-hours chat',
    ],

    'rate_review' => [
        'callback_promise' => 'Fast callback — we aim to call you within 2 business hours.',
    ],

    'google_reviews' => [
        'rating' => 5.0,
        'count' => 18,
        'profile_url' => 'https://www.google.com/search?q=Riskwisdom+Loans+reviews',
        'widget_embed' => env('GOOGLE_REVIEWS_WIDGET_EMBED'),
        'highlights' => [
            [
                'quote' => 'Clear guidance, fast follow-up, and a smoother loan process from enquiry through to settlement.',
                'author' => 'Recent borrower review',
            ],
            [
                'quote' => 'Professional advice with practical explanations that made refinancing far easier to understand.',
                'author' => 'Recent refinance client',
            ],
        ],
    ],

    'lender_panel' => [
        'title' => 'Trusted across major banks, challenger lenders, and specialist finance providers.',
        'items' => [
            'ANZ',
            'CBA',
            'Westpac',
            'NAB',
            'Macquarie',
            'ING',
            'Suncorp',
            'Bankwest',
        ],
    ],

    'how_it_works' => [
        'eyebrow' => 'How it works',
        'heading' => 'Contact our mortgage specialist now to start your savings journey.',
        'steps' => [
            [
                'image' => 'images/process/step-01-contact.svg',
                'title' => 'Contact us to get started!',
                'description' => 'Complete an enquiry form and our mortgage specialist will be automatically notified. It is that <strong>easy!</strong>',
            ],
            [
                'image' => 'images/process/step-02-advice.svg',
                'title' => 'Your best interest in mind',
                'description' => 'Our mortgage specialist will identify your key needs and advise on mortgage products that <strong>best suit your interests.</strong>',
            ],
            [
                'image' => 'images/process/step-03-documents.svg',
                'title' => 'Digital collection',
                'description' => 'A secure link will be sent to obtain details and documentation. <strong>No pesky printing required!</strong>',
            ],
            [
                'image' => 'images/process/step-04-settlement.svg',
                'title' => 'Application submission',
                'description' => 'In less than 24 hours your application can be submitted with <strong>market leading rates.</strong>',
            ],
        ],
    ],

    'about' => [
        'eyebrow' => 'About Riskwisdom Loans',
        'heading' => 'Practical lending guidance built around real borrower decisions.',
        'story' => [
            'Riskwisdom Loans was built around a simple belief: borrowers make better decisions when the advice is clear, practical, and aligned to their real goals.',
            'Whether the next step is a first purchase, refinance, investment loan, or business finance conversation, the focus is on understanding the client first and matching that to lender strategy second.',
        ],
        'principles' => [
            'Explain lending structures clearly, without jargon or pressure.',
            'Tailor the finance pathway to the borrower’s timing, risk profile, and goals.',
            'Stay responsive through documents, lender communication, and settlement.',
        ],
    ],

    'download_guides' => [
        'first-home-buyers-guide' => [
            'title' => 'First Home Buyer\'s Guide',
            'heading' => 'Download the First Home Buyer\'s Guide',
            'description' => 'A practical guide to deposits, grants, pre-approval, documents, and what to expect before you make an offer.',
            'cta' => 'Get the guide',
            'loan_type' => 'home_purchase',
            'timeline' => 'researching',
            'file' => 'guide-files/first-home-buyers-guide.html',
            'tag' => 'guide-fhb',
        ],
        'construction-knockdown-rebuild-finance-guide' => [
            'title' => 'Construction & Knockdown-Rebuild Finance Guide',
            'heading' => 'Download the Construction & Knockdown-Rebuild Finance Guide',
            'description' => 'Understand progress payments, lender stages, equity, buffers, and how construction finance differs from a standard home loan.',
            'cta' => 'Get the construction guide',
            'loan_type' => 'home_purchase',
            'timeline' => 'researching',
            'file' => 'guide-files/construction-knockdown-rebuild-finance-guide.html',
            'tag' => 'guide-construction',
        ],
    ],

    'newsletter' => [
        'tag' => 'newsletter',
        'title' => 'Get rate updates and practical mortgage tips',
        'description' => 'Receive useful home loan updates, refinance tips, and borrower guidance from Riskwisdom Loans.',
    ],

    'chat_widget' => [
        'title' => 'Questions after hours?',
        'message' => 'Leave your details and a short message. We will follow up on the next business day.',
        'button_label' => 'After-hours help',
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
