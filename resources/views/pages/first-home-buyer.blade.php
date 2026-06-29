@extends('layouts.page')

@php
    $eyebrow = 'First Home Buyer';
    $heading = 'First home buyer loans Australia — step-by-step guidance.';
    $lead = 'Buying your first property in Australia can feel overwhelming. We help first home buyers understand borrowing capacity, grants, documentation, and the path from pre-approval to settlement.';
    $intent = 'first_home_buyer';
    $ctaHref = route('book');
    $ctaHeading = 'Book a free first home buyer call';
    $ctaCopy = 'Speak with a broker about your deposit, borrowing capacity, and the steps to buy your first home.';
    $ctaLabel = 'Book a call';
    $secondaryCtaHref = route('tools.borrowing-power');
    $secondaryCtaLabel = 'Borrowing power calculator';
    $relatedLinks = [
        ['href' => route('pages.home-loans'), 'label' => 'Home loans Australia'],
        ['href' => route('tools.stamp-duty'), 'label' => 'Stamp duty calculator'],
        ['href' => route('guides.show', 'first-home-buyer-checklist-australia'), 'label' => 'First home buyer checklist'],
        ['href' => contact_url('first_home_buyer'), 'label' => 'Send an enquiry'],
    ];
    $bullets = [
        'Deposit and borrowing capacity planning for first home buyers',
        'Guidance on first home owner grants and schemes where applicable',
        'Pre-approval support before you make an offer',
        'Clear explanations of each stage from application to settlement',
    ];
    $faqs = [
        ['question' => 'What documents do first home buyers need?', 'answer' => 'Typically ID, payslips or tax returns, bank statements, savings history, and details of assets and liabilities. Requirements vary by lender and employment type.'],
        ['question' => 'Can I buy with less than a 20% deposit?', 'answer' => 'Often yes, though lenders mortgage insurance may apply. Government schemes and guarantor options may also be available depending on eligibility.'],
        ['question' => 'Should I get pre-approval before inspecting properties?', 'answer' => 'Pre-approval is strongly recommended. It clarifies your budget and helps you act quickly when you find the right property.'],
        ['question' => 'How much can I borrow as a first home buyer?', 'answer' => 'It depends on income, expenses, deposit, and lender policy. Use our borrowing power calculator for a guide, then speak with a broker for an accurate assessment.'],
    ];
@endphp

@section('title', 'First Home Buyer Loans Australia | Riskwisdom Loans')
@section('meta_description', 'First home buyer loans Australia — deposits, pre-approval, grants, and step-by-step broker guidance for your first property purchase.')
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
