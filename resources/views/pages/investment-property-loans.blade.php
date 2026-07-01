@extends('layouts.landing')

@php
    $eyebrow = 'Investment Property';
    $heading = 'Investment property loans Australia';
    $lead = 'Strategic loan support for Australian investors comparing structure, cash flow, interest-only options, and portfolio growth pathways.';
    $intent = 'investment';
    $heroImage = 'images/landing/investment-property-advisor.jpg';
    $heroImageAlt = 'Property investors reviewing investment loan options';
    $whyChooseHeading = 'Why choose us?';
    $whyChooseIntro = 'First investment or growing a portfolio? We help you compare lender policy and loan structure.';
    $whyChooseListTitle = 'A Riskwisdom investment loan review includes:';
    $ctaHref = contact_url('investment');
    $ctaHeading = 'Review your investment loan options';
    $ctaCopy = 'Share your portfolio goals and we will follow up with tailored lending guidance.';
    $ctaLabel = 'Get free loan review';
    $secondaryCtaHref = route('tools.borrowing-power');
    $secondaryCtaLabel = 'Borrowing power calculator';
    $relatedLinks = [
        ['href' => route('pages.home-loans'), 'label' => 'Home loans Australia'],
        ['href' => route('pages.refinance'), 'label' => 'Refinance home loan'],
        ['href' => route('guides.show', 'how-much-can-i-borrow-australia'), 'label' => 'How much can I borrow?'],
    ];
    $whyChooseBullets = [
        'Investment loan comparison across major lenders',
        'Interest-only and principal & interest structuring',
        'Equity release and portfolio growth planning',
        'Guidance aligned to rental income and tax considerations',
    ];
    $bullets = $whyChooseBullets;
    $faqs = [
        ['question' => 'How much deposit do I need for an investment property?', 'answer' => 'Many lenders require 10–20% deposit plus costs for investment properties. Requirements vary based on rental income, existing debt, and lender policy.'],
        ['question' => 'Is interest-only still available for investors?', 'answer' => 'Yes, though policies differ by lender and loan type. We help you understand available terms and how they affect cash flow.'],
        ['question' => 'Can I use equity from my home to invest?', 'answer' => 'Equity release is a common strategy. We assess your position and compare lenders who support portfolio growth.'],
        ['question' => 'Is my loan more expensive when using a broker?', 'answer' => 'No. Rates and fees from a lender are generally the same whether you go direct or through a broker. Broker remuneration is paid by the lender, not added as a separate client fee.'],
    ];
@endphp

@section('title', 'Investment Property Loans Australia | Riskwisdom Loans')
@section('meta_description', 'Investment property loan broker for Australian investors. Compare structure, cash flow, and lender options for your portfolio.')
@section('canonical', route('pages.investment'))

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
