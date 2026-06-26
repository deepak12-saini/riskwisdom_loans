@extends('layouts.page')

@section('title', 'Referral Partners | Riskwisdom Loans')
@section('meta_description', 'Partner with Riskwisdom Loans. Referral program for accountants, buyers agents, real estate professionals, and advisers across Australia.')
@section('canonical', route('pages.partners'))

@section('page_content')
    <span class="rw-section-label">Referral Partners</span>
    <h1>Partner with Riskwisdom Loans</h1>
    <p class="rw-page-lead">
        We work with accountants, buyers agents, real estate professionals, and financial advisers who want
        reliable finance support for their clients across Australia.
    </p>

    <ul class="rw-page-bullets">
        <li>Responsive communication and clear client updates</li>
        <li>Practical guidance across home loans, refinance, investment, and commercial finance</li>
        <li>Professional service that reflects well on your referral</li>
        <li>Dedicated point of contact for partner enquiries</li>
    </ul>

    <div class="rw-faq">
        <h2>Who we partner with</h2>
        <details class="rw-faq__item">
            <summary>Accountants & tax advisers</summary>
            <p>Support clients with structure, refinancing, and investment lending aligned to broader financial plans.</p>
        </details>
        <details class="rw-faq__item">
            <summary>Buyers agents & real estate professionals</summary>
            <p>Help clients secure finance readiness before purchase and smooth path to settlement.</p>
        </details>
        <details class="rw-faq__item">
            <summary>Financial planners & wealth advisers</summary>
            <p>Coordinate lending strategy with long-term wealth and property goals.</p>
        </details>
    </div>

    <div class="rw-page-cta-band">
        <h2>Become a referral partner</h2>
        <p>Contact us to discuss how we can support your clients and referral process.</p>
        <a class="rw-button rw-button--solid" href="{{ contact_url('commercial') }}" data-cta="partners-cta">Discuss partnership</a>
    </div>
@endsection
