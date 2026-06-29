@extends('layouts.page')

@php
    $eyebrow = 'Refinance offers';
    $heading = 'Refinance home loan cashback and lender offers — worth it?';
    $lead = 'Some lenders promote refinance cashback or switching incentives. We help you compare these refinance home loan offers against the rate, fees, and features — so you know the true value, not just the headline bonus.';
    $intent = 'refinance';
    $ctaHref = route('rate-review');
    $ctaHeading = 'Compare offers for your loan';
    $ctaCopy = 'Share your current lender and balance. We will check whether cashback refinance offers or a lower-rate option leaves you better off overall.';
    $ctaLabel = 'Request free rate review';
    $secondaryCtaHref = route('pages.refinance-rates');
    $secondaryCtaLabel = 'Refinance rates guide';
    $relatedLinks = [
        ['href' => route('pages.refinance'), 'label' => 'Refinance overview'],
        ['href' => route('tools.repayment-calculator'), 'label' => 'Repayment calculator'],
        ['href' => route('guides.show', 'refinance-readiness-checklist'), 'label' => 'Refinance readiness checklist'],
    ];
    $bullets = [
        'Compare cashback against rate savings over your loan term',
        'Check eligibility, clawback periods, and conditions',
        'Factor discharge and application fees into the true benefit',
        'Align the loan structure with your goals — not just the promotion',
    ];
    $faqs = [
        ['question' => 'What are refinance cashback offers?', 'answer' => 'Some lenders pay a cash incentive when you refinance to them. Amounts and conditions vary. The cashback must be weighed against the rate, fees, and features over the life of the loan.'],
        ['question' => 'Is cashback better than a lower interest rate?', 'answer' => 'Not always. A slightly higher rate with cashback can cost more long term than a lower rate with no incentive. We model both scenarios for your loan size and term.'],
        ['question' => 'Are there strings attached?', 'answer' => 'Often yes — minimum loan amounts, clawback if you leave within a set period, and specific product requirements. We explain conditions before you proceed.'],
        ['question' => 'How do I find the best refinance offer?', 'answer' => 'Start with a rate review. We compare cashback promotions alongside standard products to find the strongest overall outcome for your situation.'],
    ];
@endphp

@section('title', 'Refinance Cashback Offers Australia | Home Loan | Riskwisdom Loans')
@section('meta_description', 'Compare refinance home loan cashback and lender offers in Australia. Free rate review — see if a promotion or lower rate wins for your mortgage.')
@section('canonical', route('pages.refinance-cashback'))

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
