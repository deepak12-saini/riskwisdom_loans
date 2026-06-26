@extends('layouts.page')

@php
    $title = 'Home Loans Australia | Riskwisdom Loans';
    $metaDescription = 'Compare home loan options with clear broker guidance for Australian buyers and homeowners. Owner-occupier finance tailored to your goals.';
    $canonical = route('pages.home-loans');
    $eyebrow = 'Home Loans';
    $heading = 'Home loan guidance for Australian buyers and homeowners.';
    $lead = 'Whether you are purchasing your first property or reviewing your current loan structure, we help you compare suitable lenders, features, and repayment options with clarity.';
    $intent = 'home_loans';
    $bullets = [
        'Owner-occupier loans with clear structure and repayment guidance',
        'Support comparing fixed, variable, and split loan options',
        'Help navigating documentation, pre-approval, and settlement',
        'Practical advice aligned to your income, deposit, and goals',
    ];
    $faqs = [
        ['question' => 'How much deposit do I need for a home loan in Australia?', 'answer' => 'Many lenders accept deposits from 5–20% depending on your situation, LMI requirements, and whether you qualify for government schemes. We help you understand what is realistic for your position.'],
        ['question' => 'What is pre-approval and why does it matter?', 'answer' => 'Pre-approval gives you a clearer borrowing limit before you make an offer. It helps you shop with confidence and can strengthen your position with sellers or agents.'],
        ['question' => 'Can you help if I am self-employed?', 'answer' => 'Yes. We work with borrowers who have complex income including contractors, business owners, and professionals with variable earnings.'],
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
