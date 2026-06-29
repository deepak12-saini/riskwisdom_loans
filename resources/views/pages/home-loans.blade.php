@extends('layouts.page')

@php
    $title = 'Home Loans Australia | Compare Options | Riskwisdom Loans';
    $metaDescription = 'Home loans Australia — compare owner-occupier options with clear broker guidance. Pre-approval, rates, and features explained for Australian buyers and homeowners.';
    $canonical = route('pages.home-loans');
    $eyebrow = 'Home Loans';
    $heading = 'Home loans Australia — guidance for buyers and homeowners.';
    $lead = 'Whether you are purchasing your first property or reviewing your current loan structure, we help you compare suitable home loans, lender features, and repayment options with clarity.';
    $intent = 'home_loans';
    $ctaHref = contact_url('home_loans');
    $ctaHeading = 'Get free home loan guidance';
    $ctaCopy = 'Share your goals and we will follow up with tailored options across Australian lenders.';
    $ctaLabel = 'Get free loan review';
    $secondaryCtaHref = route('tools.borrowing-power');
    $secondaryCtaLabel = 'Borrowing power calculator';
    $relatedLinks = [
        ['href' => route('pages.first-home-buyer'), 'label' => 'First home buyer loans'],
        ['href' => route('pages.refinance'), 'label' => 'Refinance home loan'],
        ['href' => route('tools.stamp-duty'), 'label' => 'Stamp duty calculator'],
        ['href' => route('guides.show', 'how-much-can-i-borrow-australia'), 'label' => 'How much can I borrow?'],
    ];
    $bullets = [
        'Owner-occupier home loans with clear structure and repayment guidance',
        'Support comparing fixed, variable, and split loan options',
        'Help navigating documentation, pre-approval, and settlement',
        'Practical advice aligned to your income, deposit, and goals',
    ];
    $faqs = [
        ['question' => 'How much deposit do I need for a home loan in Australia?', 'answer' => 'Many lenders accept deposits from 5–20% depending on your situation, LMI requirements, and whether you qualify for government schemes. We help you understand what is realistic for your position.'],
        ['question' => 'What is pre-approval and why does it matter?', 'answer' => 'Pre-approval gives you a clearer borrowing limit before you make an offer. It helps you shop with confidence and can strengthen your position with sellers or agents.'],
        ['question' => 'Can you help if I am self-employed?', 'answer' => 'Yes. We work with borrowers who have complex income including contractors, business owners, and professionals with variable earnings.'],
        ['question' => 'How do home loan rates work in Australia?', 'answer' => 'Rates vary by lender, loan-to-value ratio, and product type. We compare headline and comparison rates plus fees so you understand the true cost.'],
    ];
@endphp

@section('title', $title)
@section('meta_description', $metaDescription)
@section('canonical', $canonical)

@section('page_content')
    @include('pages._landing-content')
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
