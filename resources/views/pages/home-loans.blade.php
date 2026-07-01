@extends('layouts.landing')

@php
    $title = 'Home Loans Australia | Compare Options | Riskwisdom Loans';
    $metaDescription = 'Home loans Australia — compare owner-occupier options with clear broker guidance. Pre-approval, rates, and features explained for Australian buyers and homeowners.';
    $canonical = route('pages.home-loans');
    $eyebrow = 'Home Loans';
    $heading = 'Home loans Australia';
    $lead = 'Expert guidance and tailored loan solutions to help you secure your home or review your current loan structure with clarity.';
    $intent = 'home_loans';
    $heroImage = 'images/landing/home-loans-advisor.jpg';
    $heroImageAlt = 'Mortgage specialist providing home loan guidance in front of an Australian home';
    $whyChooseHeading = 'Why choose us?';
    $whyChooseIntro = 'Self-employed, employee, tradie, first-home buyer? No problems.';
    $whyChooseListTitle = 'A Riskwisdom home loan has:';
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
    $whyChooseBullets = [
        'Competitive interest rates across major banks and specialist lenders',
        'Support to maximise borrowing capacity where your situation allows',
        'Clear guidance on fixed, variable, and split loan structures',
        'Practical help from pre-approval through to settlement',
    ];
    $bullets = $whyChooseBullets;
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
    @include('pages._landing-content-body')
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
