@extends('layouts.page')

@section('title', 'Privacy Policy | Riskwisdom Loans')
@section('meta_description', 'Privacy policy for Riskwisdom Loans and Risk Wisdom Loans Pty Ltd.')
@section('canonical', route('pages.privacy'))

@section('page_content')
    <h1>Privacy Policy</h1>
    <p class="rw-page-lead">Last updated: {{ now()->format('j F Y') }}</p>

    <div class="rw-article__content">
        <p>{{ config('riskwisdom.legal_name') }} ("we", "us", "Riskwisdom Loans") respects your privacy and is committed to protecting personal information in accordance with the Australian Privacy Act 1988 and the Australian Privacy Principles.</p>

        <h2>Information we collect</h2>
        <p>We may collect personal information you provide through our website contact form, phone, email, or during a finance enquiry. This may include your name, contact details, loan requirements, financial information relevant to your enquiry, and communications with us.</p>

        <h2>How we use your information</h2>
        <ul>
            <li>To respond to enquiries and provide finance broking services</li>
            <li>To assess lending options with lenders on your behalf</li>
            <li>To comply with legal and regulatory obligations</li>
            <li>To improve our website and client experience</li>
        </ul>

        <h2>Disclosure</h2>
        <p>We may disclose your information to lenders, aggregators, valuers, insurers, and professional advisers as required to provide broking services. We do not sell your personal information.</p>

        <h2>Website analytics</h2>
        <p>We may use analytics tools (such as Google Analytics via Google Tag Manager and Microsoft Clarity) to understand how visitors use our website. These services may collect anonymised usage data subject to their own privacy policies.</p>

        <h2>Security</h2>
        <p>We take reasonable steps to protect personal information from misuse, loss, and unauthorised access.</p>

        <h2>Access and complaints</h2>
        <p>To access or correct your information, or to make a privacy complaint, contact us at <a href="mailto:{{ config('riskwisdom.email') }}">{{ config('riskwisdom.email') }}</a> or {{ config('riskwisdom.phone') }}.</p>
    </div>
@endsection
