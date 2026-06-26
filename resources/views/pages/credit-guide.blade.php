@extends('layouts.page')

@section('title', 'Credit Guide | Riskwisdom Loans')
@section('meta_description', 'Credit guide and important information for borrowers using Riskwisdom Loans broking services in Australia.')
@section('canonical', route('pages.credit-guide'))

@section('page_content')
    <h1>Credit Guide</h1>
    <p class="rw-page-lead">Important information about our credit assistance services.</p>

    <div class="rw-article__content">
        <p>This Credit Guide provides information about the credit assistance services offered by {{ config('riskwisdom.legal_name') }} trading as {{ config('riskwisdom.brand_name') }}.</p>

        <h2>Who we are</h2>
        <p><strong>Legal entity:</strong> {{ config('riskwisdom.legal_name') }}<br>
        <strong>Trading name:</strong> {{ config('riskwisdom.brand_name') }}<br>
        <strong>Contact:</strong> {{ config('riskwisdom.email') }} · {{ config('riskwisdom.phone') }}</p>

        <h2>Australian Credit Licence</h2>
        <p>
            @if (env('ACL_NUMBER'))
                We hold Australian Credit Licence number {{ env('ACL_NUMBER') }}.
            @else
                Australian Credit Licence details will be published here once confirmed. Please contact us for current licensing information.
            @endif
        </p>

        <h2>Credit assistance we provide</h2>
        <p>We provide credit assistance by helping you apply for or obtain credit products such as home loans, refinance loans, investment property loans, commercial finance, and related lending products from licensed lenders.</p>

        <h2>How we are paid</h2>
        <p>We are typically paid commissions by lenders when a loan settles. In some cases a fee may also apply. We will disclose any fees and commissions relevant to your application before you proceed.</p>

        <h2>Your obligations</h2>
        <p>You must provide accurate and complete information. Giving false or misleading information is an offence under Australian law and may affect your application.</p>

        <h2>Complaints</h2>
        <p>If you have a complaint about our services, contact us at {{ config('riskwisdom.email') }}. If we cannot resolve your complaint, you may refer it to the Australian Financial Complaints Authority (AFCA) at <a href="https://www.afca.org.au" target="_blank" rel="noreferrer">afca.org.au</a>.</p>

        <h2>General advice warning</h2>
        <p>Information on this website is general in nature and does not constitute personal financial advice. You should consider your own objectives, financial situation, and needs before acting on any information.</p>
    </div>
@endsection
