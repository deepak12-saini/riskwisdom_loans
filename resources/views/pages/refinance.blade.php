@extends('layouts.page')

@php
    $eyebrow = 'Refinance';
    $heading = 'Refinance home loan Australia — compare rates and switching options.';
    $lead = 'If you are reviewing your mortgage rate, features, or repayments, we help Australian homeowners compare refinance home loan options and understand whether switching lenders makes financial sense.';
    $intent = 'refinance';
    $ctaHref = route('rate-review');
    $ctaHeading = 'Am I on the right rate?';
    $ctaCopy = 'Request a free rate review and we will call you back quickly to compare your current loan against today\'s refinance home loan rates.';
    $ctaLabel = 'Request free rate review';
    $secondaryCtaHref = route('tools.repayment-calculator');
    $secondaryCtaLabel = 'Repayment calculator';
    $relatedLinks = [
        ['href' => route('pages.refinance-rates'), 'label' => 'Refinance home loan rates'],
        ['href' => route('pages.refinance-calculator'), 'label' => 'Refinance home loan calculator'],
        ['href' => route('pages.refinance-cashback'), 'label' => 'Refinance cashback offers'],
        ['href' => route('guides.show', 'when-to-refinance-home-loan-australia'), 'label' => 'When to refinance guide'],
    ];
    $bullets = [
        'Compare refinance home loan rates and features across multiple lenders',
        'Break cost and switching cost assessment before you move',
        'Debt consolidation and cash-out refinance guidance',
        'Support for homeowners wanting lower repayments or better flexibility',
    ];
    $faqs = [
        ['question' => 'What does refinance home loan mean?', 'answer' => 'Refinancing means replacing your existing mortgage with a new loan — often with a different lender — to secure a better rate, features, or structure.'],
        ['question' => 'When is refinancing worth it?', 'answer' => 'It depends on your current rate, remaining loan term, break costs, and goals. A small rate reduction on a large loan can save thousands, but fees and lost features matter too.'],
        ['question' => 'How do refinance home loan rates compare?', 'answer' => 'Rates change frequently. We compare your current loan against competitive offers and explain the comparison rate, fees, and features — not just the headline number.'],
        ['question' => 'Can I refinance to consolidate debt or buy a car?', 'answer' => 'Cash-out refinance may let you access equity for debt consolidation or major purchases. We assess whether the structure suits your goals and lender policy.'],
        ['question' => 'How long does refinancing take in Australia?', 'answer' => 'Most refinances take 2–6 weeks depending on lender workload, valuations, and documentation. We help keep the process organised.'],
    ];
@endphp

@section('title', 'Refinance Home Loan Australia | Compare Rates | Riskwisdom Loans')
@section('meta_description', 'Refinance home loan Australia — compare rates, features, and switching costs. Free rate review for homeowners reviewing their mortgage.')
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
