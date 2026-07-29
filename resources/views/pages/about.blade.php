@extends('layouts.landing')

@php
    $about = config('riskwisdom.about');
    $skipLandingHero = true;
    $aboutBrokerSection = true;
    $refinanceLanding = config('riskwisdom.conversion_landings.refinance', []);
    $broker = $refinanceLanding['broker'] ?? [
        'name' => 'Kaltaran Bhinder',
        'tagline' => 'Mortgage broker since 2004',
        'credential' => 'Credit representative 399434',
        'avatar' => 'images/landing/kaltaran-bhinder.png',
        'avatar_alt' => 'Kaltaran Bhinder, mortgage broker',
    ];
    $brokerHeadline = 'Mortgage Broker';
    $brokerLead = $refinanceLanding['subheadline']
        ?? 'Riskwisdom Loans is a mortgage broker comparing home loans from major banks and specialist lenders at no cost to you.';
    $brokerLeadExtra = $refinanceLanding['subheadline_extra']
        ?? 'Led by Kaltaran Bhinder, we specialise in finding you a better rate and structuring finance around your goals.';
    $brokerCampaign = 'default';
    $brokerLanding = array_merge(config('riskwisdom.conversion_landings.default', []), [
        'form_headline' => 'Get in touch',
        'form_intro' => 'An expert will call you back. Our service is 100% free to you — we receive a commission from the lender.',
        'form_show_pill' => false,
        'form_cta' => 'Request my free callback',
        'default_loan_type' => null,
    ]);
    $intent = null;
    $heroImage = 'images/landing/about-broker-team.jpg';
    $heroImageAlt = 'Riskwisdom Loans broker providing clear home loan guidance';
    $whyChooseHeading = 'Why borrowers choose us';
    $whyChooseIntro = 'We focus on clarity, responsiveness, and finance options that fit your timing — not pressure or jargon.';
    $whyChooseListTitle = 'What you can expect:';
    $ctaHref = route('book');
    $ctaHeading = 'Ready to talk through your loan options?';
    $ctaCopy = 'Book a free call, request a rate review, or send an enquiry — we will help you understand the next best step.';
    $ctaLabel = 'Book a call';
    $secondaryCtaHref = route('rate-review');
    $secondaryCtaLabel = 'Free rate review';
    $relatedLinks = [];
    $whyChooseBullets = $about['principles'] ?? [];
    $bullets = $whyChooseBullets;
    $faqs = [
        [
            'question' => 'What does Riskwisdom Loans do?',
            'answer' => 'We help Australian borrowers compare home loans, refinancing, investment lending, and commercial finance with clear guidance from enquiry through to settlement.',
        ],
        [
            'question' => 'Is your advice tailored to my situation?',
            'answer' => 'Yes. We start by understanding your goals, income, timing, and priorities before recommending suitable lender pathways.',
        ],
        [
            'question' => 'How quickly will you respond?',
            'answer' => 'We aim to respond to enquiries and rate review requests quickly during business hours, with after-hours messages followed up the next business day.',
        ],
        [
            'question' => 'Does it cost more to use a broker?',
            'answer' => 'In most cases, lender rates and fees are the same whether you go direct or through a broker. Broker remuneration is generally paid by the lender, not added as a separate client fee.',
        ],
    ];
@endphp

@section('title', 'About Riskwisdom Loans | Riskwisdom Loans')
@section('meta_description', 'Meet Kaltaran Bhinder and learn about Riskwisdom Loans — practical lending guidance for Australian borrowers.')
@section('canonical', route('pages.about'))

@section('page_content')
    @include('pages._landing-about-body')
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
