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
        'conversion' => 'Ad / conversion landing',
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
        'enquire' => 'enquire.show',
        'enquire_refinance' => ['enquire.campaign', ['campaign' => 'refinance']],
        'enquire_home_loans' => ['enquire.campaign', ['campaign' => 'home-loans']],
        'enquire_fhb' => ['enquire.campaign', ['campaign' => 'first-home-buyer']],
        'enquire_investment' => ['enquire.campaign', ['campaign' => 'investment']],
        'enquire_commercial' => ['enquire.campaign', ['campaign' => 'commercial']],
    ],

    /*
    |--------------------------------------------------------------------------
    | Conversion landing pages (Google Ads, Meta, paid campaigns)
    |--------------------------------------------------------------------------
    | Minimal-distraction pages with headline + pre-qual enquiry form.
    | URLs: /enquire, /enquire/refinance, /enquire/home-loans, etc.
    */
    'conversion_landings' => [
        'default' => [
            'title' => 'Free Loan Enquiry | Riskwisdom Loans',
            'meta_description' => 'Tell us what you need and a Riskwisdom Loans broker will call you back with clear, no-obligation guidance for Australian borrowers.',
            'eyebrow' => 'Free loan enquiry',
            'headline' => 'Get clear home loan advice — without the runaround.',
            'subheadline' => 'Tell us what you are looking for and a broker will call you back with practical next steps. No pressure, no jargon.',
            'default_loan_type' => null,
            'image' => 'images/landing/home-loans-advisor.jpg',
            'image_alt' => 'Mortgage broker consulting with a client',
            'benefits' => [
                'Compare options across major banks and specialist lenders',
                'Fast callback — we aim to call within 2 business hours',
                'Australian borrowers only · No obligation',
            ],
            'trust_badges' => [
                ['label' => '5.0 Google rating', 'value' => '18+ reviews'],
                ['label' => 'Fast callback', 'value' => '2 business hours'],
                ['label' => 'No cost to you', 'value' => 'Broker service'],
            ],
            'form_headline' => 'Tell us what you need',
            'form_intro' => 'Takes about 60 seconds. We will call the number you provide.',
            'form_cta' => 'Get my free callback',
        ],
        'refinance' => [
            'title' => 'Refinance Home Loan Enquiry | Riskwisdom Loans',
            'meta_description' => 'Thinking about refinancing? Tell us your goals and get a free, no-obligation callback from an Australian mortgage broker.',
            'eyebrow' => 'Refinance enquiry',
            'headline' => 'Could you be paying less on your home loan?',
            'subheadline' => 'Share what you are looking for and we will check whether refinancing could save you money — clear advice, no obligation.',
            'default_loan_type' => 'refinance',
            'image' => 'images/landing/refinance-advisor.jpg',
            'image_alt' => 'Broker reviewing refinance options with a homeowner',
            'benefits' => [
                'Compare your rate against current lender offers',
                'Understand break costs before you switch',
                'Cash-out and debt consolidation options explained clearly',
            ],
            'trust_badges' => [
                ['label' => 'Rate review', 'value' => 'Free & no obligation'],
                ['label' => 'Multi-lender', 'value' => 'Panel access'],
                ['label' => 'Australian', 'value' => 'Licensed broker'],
            ],
            'form_headline' => 'Start your refinance enquiry',
            'form_intro' => 'Tell us what you want to achieve and we will call you back quickly.',
            'form_cta' => 'Request my free refinance review',
        ],
        'home-loans' => [
            'title' => 'Home Loan Enquiry | Riskwisdom Loans',
            'meta_description' => 'Buying or upgrading your home? Tell us what you need and get a free callback from a Riskwisdom Loans broker.',
            'eyebrow' => 'Home loan enquiry',
            'headline' => 'Find the right home loan — with a broker on your side.',
            'subheadline' => 'Whether you are buying, upgrading, or reviewing your loan, tell us your goals and we will outline clear next steps.',
            'default_loan_type' => 'home_purchase',
            'image' => 'images/landing/home-loans-advisor.jpg',
            'image_alt' => 'Home loan advisor helping a borrower',
            'benefits' => [
                'Owner-occupier loans tailored to your situation',
                'Pre-approval guidance before you make an offer',
                'Compare lenders without applying everywhere yourself',
            ],
            'trust_badges' => [
                ['label' => 'First purchase', 'value' => 'Step-by-step help'],
                ['label' => 'Pre-approval', 'value' => 'Guidance included'],
                ['label' => 'No pressure', 'value' => 'Clear advice'],
            ],
            'form_headline' => 'Tell us about your home loan goals',
            'form_intro' => 'What are you looking for? We will match you with the right conversation.',
            'form_cta' => 'Get my free home loan callback',
        ],
        'first-home-buyer' => [
            'title' => 'First Home Buyer Enquiry | Riskwisdom Loans',
            'meta_description' => 'Buying your first home in Australia? Tell us where you are up to and get practical, no-pressure guidance from a mortgage broker.',
            'eyebrow' => 'First home buyer',
            'headline' => 'Your first home — explained clearly, step by step.',
            'subheadline' => 'Grants, deposits, pre-approval, and lender choice can feel overwhelming. Tell us your situation and we will help you understand the path ahead.',
            'default_loan_type' => 'home_purchase',
            'image' => 'images/landing/home-loans-advisor.jpg',
            'image_alt' => 'First home buyer receiving broker guidance',
            'benefits' => [
                'Understand borrowing capacity and deposit requirements',
                'Grants and stamp duty concessions explained in plain English',
                'Support from enquiry through to settlement',
            ],
            'trust_badges' => [
                ['label' => 'First home', 'value' => 'Specialist guidance'],
                ['label' => 'Grants & schemes', 'value' => 'Explained clearly'],
                ['label' => 'No obligation', 'value' => 'Free consult'],
            ],
            'form_headline' => 'Tell us where you are up to',
            'form_intro' => 'Share your goals and timeline — we will call you back with practical next steps.',
            'form_cta' => 'Get my first home buyer callback',
        ],
        'investment' => [
            'title' => 'Investment Property Loan Enquiry | Riskwisdom Loans',
            'meta_description' => 'Building or growing your property portfolio? Tell us your investment goals and speak with a broker who understands investor lending.',
            'eyebrow' => 'Investment property',
            'headline' => 'Investment property finance — structured for your portfolio.',
            'subheadline' => 'Tell us what you are looking to do and we will outline lender options, structuring considerations, and clear next steps.',
            'default_loan_type' => 'investment',
            'image' => 'images/landing/investment-property-advisor.jpg',
            'image_alt' => 'Investment property finance consultation',
            'benefits' => [
                'Interest-only and portfolio lending options',
                'Equity release and next-purchase strategy',
                'Lender policy matched to your investor profile',
            ],
            'trust_badges' => [
                ['label' => 'Investors', 'value' => 'Specialist focus'],
                ['label' => 'Portfolio', 'value' => 'Growth strategies'],
                ['label' => 'Multi-lender', 'value' => 'Panel access'],
            ],
            'form_headline' => 'Tell us about your investment goals',
            'form_intro' => 'What property finance do you need? We will call you back to discuss options.',
            'form_cta' => 'Get my investment loan callback',
        ],
        'commercial' => [
            'title' => 'Commercial Finance Enquiry | Riskwisdom Loans',
            'meta_description' => 'Business or commercial property finance enquiry. Tell us what you need and get a callback from a Riskwisdom Loans broker.',
            'eyebrow' => 'Commercial finance',
            'headline' => 'Commercial finance — clear advice for business owners.',
            'subheadline' => 'From commercial property to business lending, tell us what you are trying to achieve and we will outline realistic finance pathways.',
            'default_loan_type' => 'commercial',
            'image' => 'images/landing/commercial-finance-advisor.jpg',
            'image_alt' => 'Commercial finance broker consultation',
            'benefits' => [
                'Commercial property and business lending guidance',
                'Construction and specialist finance conversations',
                'Responsive support through approval and settlement',
            ],
            'trust_badges' => [
                ['label' => 'Business owners', 'value' => 'Tailored advice'],
                ['label' => 'Specialist', 'value' => 'Lender access'],
                ['label' => 'Fast follow-up', 'value' => '2 business hours'],
            ],
            'form_headline' => 'Tell us about your finance needs',
            'form_intro' => 'Describe what you are looking for and we will call you back.',
            'form_cta' => 'Get my commercial finance callback',
        ],
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
