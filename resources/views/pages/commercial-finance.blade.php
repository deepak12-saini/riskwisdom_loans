@extends('layouts.landing')

@php
    $eyebrow = 'Commercial Finance';
    $heading = 'Commercial finance Australia';
    $lead = 'Tailored lending support for commercial property, business growth, equipment, and working capital with clear guidance from enquiry to approval.';
    $intent = 'commercial';
    $heroImage = 'images/landing/commercial-finance-advisor.jpg';
    $heroImageAlt = 'Business owner discussing commercial finance options';
    $whyChooseHeading = 'Why choose us?';
    $whyChooseIntro = 'Self-employed, company director, or growing operator? We understand business lending.';
    $whyChooseListTitle = 'A Riskwisdom commercial finance solution can include:';
    $ctaHref = contact_url('commercial');
    $ctaHeading = 'Discuss your commercial finance needs';
    $ctaCopy = 'Tell us about your business goals and we will follow up with practical lending options.';
    $ctaLabel = 'Get free loan review';
    $secondaryCtaHref = route('pages.partners');
    $secondaryCtaLabel = 'Referral partners';
    $relatedLinks = [
        ['href' => route('pages.home-loans'), 'label' => 'Home loans Australia'],
        ['href' => route('pages.investment'), 'label' => 'Investment property loans'],
        ['href' => route('tools.borrowing-power'), 'label' => 'Borrowing power calculator'],
    ];
    $whyChooseBullets = [
        'Commercial property and business acquisition finance',
        'Asset and equipment funding options',
        'Support for self-employed and company structures',
        'Practical guidance on documentation and lender expectations',
    ];
    $bullets = $whyChooseBullets;
    $faqs = [
        ['question' => 'What is the difference between commercial and residential loans?', 'answer' => 'Commercial loans often have different assessment criteria, terms, and documentation requirements. Lenders focus on business cash flow, asset type, and security.'],
        ['question' => 'Can a new business get commercial finance?', 'answer' => 'It depends on trading history, security, and industry. Some lenders support newer businesses with strong assets or guarantor support.'],
        ['question' => 'Do you help with equipment finance?', 'answer' => 'Yes. We compare asset finance options for vehicles, plant, and equipment with a focus on usability and cash flow.'],
        ['question' => 'Why choose a broker for commercial finance?', 'answer' => 'A broker can compare lender policies, structure options, and documentation requirements so you focus on the finance pathway that fits your business goals.'],
    ];
@endphp

@section('title', 'Commercial Finance Australia | Riskwisdom Loans')
@section('meta_description', 'Commercial property and business finance for Australian owners. Compare lending options for property, equipment, and growth.')
@section('canonical', route('pages.commercial'))

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
