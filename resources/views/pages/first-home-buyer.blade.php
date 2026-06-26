@extends('layouts.page')

@php
    $eyebrow = 'First Home Buyer';
    $heading = 'First home buyer loans with step-by-step guidance.';
    $lead = 'Buying your first property in Australia can feel overwhelming. We help first home buyers understand borrowing capacity, grants, documentation, and the path from pre-approval to settlement.';
    $intent = 'first_home_buyer';
    $bullets = [
        'Deposit and borrowing capacity planning',
        'Guidance on first home owner grants and schemes where applicable',
        'Pre-approval support before you make an offer',
        'Clear explanations of each stage from application to settlement',
    ];
    $faqs = [
        ['question' => 'What documents do first home buyers need?', 'answer' => 'Typically ID, payslips or tax returns, bank statements, savings history, and details of assets and liabilities. Requirements vary by lender and employment type.'],
        ['question' => 'Can I buy with less than a 20% deposit?', 'answer' => 'Often yes, though lenders mortgage insurance may apply. Government schemes and guarantor options may also be available depending on eligibility.'],
        ['question' => 'Should I get pre-approval before inspecting properties?', 'answer' => 'Pre-approval is strongly recommended. It clarifies your budget and helps you act quickly when you find the right property.'],
    ];
@endphp

@section('title', 'First Home Buyer Loans Australia | Riskwisdom Loans')
@section('meta_description', 'First home buyer loan guidance for Australians. Understand deposits, pre-approval, grants, and the path to your first property purchase.')
@section('canonical', route('pages.first-home-buyer'))

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
