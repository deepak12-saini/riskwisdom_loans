@extends('layouts.landing')

@php
    $eyebrow = 'Referral Partners';
    $heading = 'Partner with Riskwisdom Loans';
    $lead = 'Work with a broker team that keeps your clients informed, supported, and looked after across home loans, refinance, investment, and commercial finance.';
    $intent = 'commercial';
    $heroImage = 'images/landing/partners-referral.jpg';
    $heroImageAlt = 'Professional referral partners discussing client finance support';
    $whyChooseHeading = 'Why partner with us?';
    $whyChooseIntro = 'Accountants, buyers agents, real estate professionals, and advisers — we make referrals simple.';
    $whyChooseListTitle = 'Your clients receive:';
    $ctaHref = contact_url('commercial');
    $ctaHeading = 'Become a referral partner';
    $ctaCopy = 'Contact us to discuss how we can support your clients and referral process.';
    $ctaLabel = 'Discuss partnership';
    $secondaryCtaHref = route('pages.about');
    $secondaryCtaLabel = 'About Riskwisdom Loans';
    $relatedLinks = [
        ['href' => route('pages.commercial'), 'label' => 'Commercial finance'],
        ['href' => route('pages.home-loans'), 'label' => 'Home loans Australia'],
        ['href' => route('pages.refinance'), 'label' => 'Refinance home loan'],
        ['href' => route('pages.about'), 'label' => 'About us'],
    ];
    $whyChooseBullets = [
        'Responsive communication and clear client updates',
        'Practical guidance across home loans, refinance, investment, and commercial finance',
        'Professional service that reflects well on your referral',
        'Dedicated point of contact for partner enquiries',
    ];
    $bullets = $whyChooseBullets;
    $faqs = [
        [
            'question' => 'Who do you partner with?',
            'answer' => 'We work with accountants, tax advisers, buyers agents, real estate professionals, financial planners, and wealth advisers who want reliable finance support for their clients.',
        ],
        [
            'question' => 'How do accountant and tax adviser referrals work?',
            'answer' => 'We support clients with loan structure, refinancing, and investment lending aligned to broader financial plans, with clear updates you can pass on to your client.',
        ],
        [
            'question' => 'Can buyers agents and real estate professionals refer clients?',
            'answer' => 'Yes. We help clients secure finance readiness before purchase and guide them through pre-approval, documentation, and settlement.',
        ],
        [
            'question' => 'How do I refer a client to Riskwisdom Loans?',
            'answer' => 'Send an enquiry through our contact form or call us directly. Share the client’s goals and timing, and we will follow up promptly with a clear next step.',
        ],
    ];
@endphp

@section('title', 'Referral Partners | Riskwisdom Loans')
@section('meta_description', 'Partner with Riskwisdom Loans. Referral program for accountants, buyers agents, real estate professionals, and advisers across Australia.')
@section('canonical', route('pages.partners'))

@section('page_content')
    @include('pages._landing-content-body')
@endsection

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
            ])->values()->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush
