@extends('layouts.page')

@php
    $eyebrow = 'Refinance rates';
    $heading = 'Refinance home loan rates — are you still on a competitive deal?';
    $lead = 'Australian homeowners often stay on revert or legacy rates that no longer stack up. We compare refinance home loan rates against your current mortgage and explain whether switching could save you money.';
    $intent = 'refinance';
    $ctaHref = route('rate-review');
    $ctaHeading = 'Check your rate in minutes';
    $ctaCopy = 'Tell us your current rate and we will call back quickly with a plain-English comparison against today\'s refinance offers.';
    $ctaLabel = 'Request free rate review';
    $secondaryCtaHref = route('pages.refinance');
    $secondaryCtaLabel = 'Refinance overview';
    $relatedLinks = [
        ['href' => route('tools.repayment-calculator'), 'label' => 'Refinance repayment calculator'],
        ['href' => route('pages.refinance-cashback'), 'label' => 'Cashback refinance offers'],
        ['href' => route('book'), 'label' => 'Book a broker call'],
    ];
    $bullets = [
        'Compare headline and comparison rates — not just the advertised number',
        'Factor in discharge, application, and ongoing fees',
        'Assess break costs on fixed loans before switching',
        'Match rate with features you actually need (offset, redraw, split)',
    ];
    $faqs = [
        ['question' => 'What are typical refinance home loan rates in Australia?', 'answer' => 'Rates move with the RBA, lender appetite, and your loan-to-value ratio. The right rate for you depends on your profile — we compare options for your situation rather than quoting a single market average.'],
        ['question' => 'Is the comparison rate important?', 'answer' => 'Yes. The comparison rate includes many fees and gives a better sense of true cost over time. We explain both the advertised rate and comparison rate when reviewing options.'],
        ['question' => 'Will I save money if I refinance for a lower rate?', 'answer' => 'Often yes, but not always. Savings depend on your loan size, remaining term, switching costs, and whether you lose valuable features. We model the break-even before you proceed.'],
        ['question' => 'How fast can I get a rate review?', 'answer' => config('riskwisdom.rate_review.callback_promise')],
    ];
@endphp

@section('title', 'Refinance Home Loan Rates Australia | Riskwisdom Loans')
@section('meta_description', 'Compare refinance home loan rates for Australian mortgages. Free rate review — see if you could save on repayments.')
@section('canonical', route('pages.refinance-rates'))

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
