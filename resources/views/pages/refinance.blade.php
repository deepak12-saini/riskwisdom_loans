@extends('layouts.page')

@php
    $eyebrow = 'Refinance';
    $heading = 'Refinance your home loan with clarity and confidence.';
    $lead = 'If you are an Australian mortgage holder reviewing your rate, features, or repayments, we help you compare refinance options and understand whether switching makes financial sense.';
    $intent = 'refinance';
    $ctaHref = route('rate-review');
    $ctaHeading = 'Am I on the right rate?';
    $ctaCopy = 'Request a free rate review and we will call you back quickly to compare your current loan.';
    $ctaLabel = 'Request free rate review';
    $bullets = [
        'Rate and feature comparison across multiple lenders',
        'Break cost and switching cost assessment',
        'Debt consolidation and cash-out refinance guidance',
        'Support for homeowners wanting lower repayments or better flexibility',
    ];
    $faqs = [
        ['question' => 'When is refinancing worth it?', 'answer' => 'It depends on your current rate, remaining loan term, break costs, and goals. A small rate reduction on a large loan can save thousands, but fees and lost features matter too.'],
        ['question' => 'How long does refinancing take in Australia?', 'answer' => 'Most refinances take 2–6 weeks depending on lender workload, valuations, and documentation. We help keep the process organised.'],
        ['question' => 'Can I refinance if my property value has changed?', 'answer' => 'Yes. Updated valuations can affect your loan-to-value ratio and available equity. We help you understand what lenders may offer based on current position.'],
    ];
@endphp

@section('title', 'Refinance Home Loan Australia | Riskwisdom Loans')
@section('meta_description', 'Review refinance options for Australian homeowners. Compare rates, features, and switching costs with practical broker guidance.')
@section('canonical', route('pages.refinance'))

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
