@extends('layouts.page')

@php
    $eyebrow = 'Refinance calculator';
    $heading = 'Refinance home loan calculator — model your repayments.';
    $lead = 'Use our repayment calculator to estimate monthly home loan repayments when you refinance. Adjust loan amount, interest rate, and term to see how different refinance scenarios could affect your budget.';
    $intent = 'refinance';
    $ctaHref = route('tools.repayment-calculator');
    $ctaHeading = 'Open the repayment calculator';
    $ctaCopy = 'Model principal and interest repayments instantly, then request a free rate review to see real lender options.';
    $ctaLabel = 'Use repayment calculator';
    $secondaryCtaHref = route('rate-review');
    $secondaryCtaLabel = 'Free rate review';
    $relatedLinks = [
        ['href' => route('pages.refinance-rates'), 'label' => 'Refinance home loan rates'],
        ['href' => route('tools.borrowing-power'), 'label' => 'Borrowing power calculator'],
        ['href' => route('pages.refinance'), 'label' => 'Refinance overview'],
    ];
    $bullets = [
        'Instant repayment estimates as you change inputs',
        'Compare a lower refinance rate against your current loan',
        'See total interest over the loan term',
        'Pair estimates with broker advice on real lender offers',
    ];
    $faqs = [
        ['question' => 'How does a refinance home loan calculator work?', 'answer' => 'You enter loan amount, interest rate, and term. The calculator estimates principal and interest repayments. It is a guide only — fees, offset accounts, and rate changes are not included.'],
        ['question' => 'Can I compare my current loan to a refinanced loan?', 'answer' => 'Yes. Run your current balance and rate, then try a lower rate or different term to see how repayments might change. For a full comparison including fees, request a rate review.'],
        ['question' => 'Is this the same as borrowing power?', 'answer' => 'No. The repayment calculator models repayments on a given loan amount. Our borrowing power calculator estimates how much you may be able to borrow based on income and expenses.'],
        ['question' => 'What should I do after using the calculator?', 'answer' => 'If the numbers look promising, request a free rate review. We will compare live lender options and explain switching costs.'],
    ];
@endphp

@section('title', 'Refinance Home Loan Calculator Australia | Riskwisdom Loans')
@section('meta_description', 'Refinance home loan calculator for Australian mortgages. Model repayments, compare rates, then request a free broker rate review.')
@section('canonical', route('pages.refinance-calculator'))

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
