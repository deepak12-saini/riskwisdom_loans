@extends('layouts.page')

@php
    $eyebrow = 'Commercial Finance';
    $heading = 'Commercial property and business finance for Australian operators.';
    $lead = 'From commercial property purchases to equipment and working capital, we help business owners compare lending pathways that suit cash flow and growth plans.';
    $intent = 'commercial';
    $bullets = [
        'Commercial property and business acquisition finance',
        'Asset and equipment funding options',
        'Support for self-employed and company structures',
        'Practical guidance on documentation and lender expectations',
    ];
    $faqs = [
        ['question' => 'What is the difference between commercial and residential loans?', 'answer' => 'Commercial loans often have different assessment criteria, terms, and documentation requirements. Lenders focus on business cash flow, asset type, and security.'],
        ['question' => 'Can a new business get commercial finance?', 'answer' => 'It depends on trading history, security, and industry. Some lenders support newer businesses with strong assets or guarantor support.'],
        ['question' => 'Do you help with equipment finance?', 'answer' => 'Yes. We compare asset finance options for vehicles, plant, and equipment with a focus on usability and cash flow.'],
    ];
@endphp

@section('title', 'Commercial Finance Australia | Riskwisdom Loans')
@section('meta_description', 'Commercial property and business finance for Australian owners. Compare lending options for property, equipment, and growth.')
@section('canonical', route('pages.commercial'))

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
