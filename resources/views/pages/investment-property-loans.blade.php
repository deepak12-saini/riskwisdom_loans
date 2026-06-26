@extends('layouts.page')

@php
    $eyebrow = 'Investment Property';
    $heading = 'Investment property loans for long-term Australian investors.';
    $lead = 'Building a property portfolio requires more than finding a low rate. We help investors compare loan structure, cash flow, interest-only options, and equity strategies.';
    $intent = 'investment';
    $bullets = [
        'Investment loan comparison across major lenders',
        'Interest-only and principal & interest structuring',
        'Equity release and portfolio growth planning',
        'Guidance aligned to rental income and tax considerations',
    ];
    $faqs = [
        ['question' => 'How much deposit do I need for an investment property?', 'answer' => 'Many lenders require 10–20% deposit plus costs for investment properties. Requirements vary based on rental income, existing debt, and lender policy.'],
        ['question' => 'Is interest-only still available for investors?', 'answer' => 'Yes, though policies differ by lender and loan type. We help you understand available terms and how they affect cash flow.'],
        ['question' => 'Can I use equity from my home to invest?', 'answer' => 'Equity release is a common strategy. We assess your position and compare lenders who support portfolio growth.'],
    ];
@endphp

@section('title', 'Investment Property Loans Australia | Riskwisdom Loans')
@section('meta_description', 'Investment property loan broker for Australian investors. Compare structure, cash flow, and lender options for your portfolio.')
@section('canonical', route('pages.investment'))

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
