@extends('layouts.page')

@section('title', $guide['title'].' | Riskwisdom Loans')
@section('meta_description', $guide['description'])
@section('canonical', route('guides.download.show', $slug))

@section('page_content')
    <span class="rw-section-label">Downloadable guide</span>
    <h1>{{ $guide['heading'] }}</h1>
    <p class="rw-page-lead">{{ $guide['description'] }}</p>

    <div class="rw-page-related">
        <h2>What you will get</h2>
        <ul class="rw-page-bullets">
            <li>Instant access on the thank-you page</li>
            <li>An email copy sent to your inbox</li>
            <li>Practical next steps you can review in your own time</li>
        </ul>
    </div>

    <form action="{{ route('guides.download.store', $slug) }}" method="post" class="rw-form rw-guide-form" id="guide-download-form">
        @csrf
        <input type="text" name="_gotcha" value="" tabindex="-1" autocomplete="off" class="rw-form-honeypot" aria-hidden="true">

        @if ($errors->any())
            <div class="rw-form-alert rw-form-alert-error">
                <strong>We could not send your guide yet.</strong>
                <ul class="rw-form-error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rw-guide-form__intro">
            <h2>Send the guide to my inbox</h2>
            <p>Enter your details below for instant access now and a copy by email.</p>
        </div>

        <div class="rw-form-grid rw-guide-form__grid">
            <label>
                <span>First name <em class="rw-required">*</em></span>
                <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required>
            </label>
            <label>
                <span>Last name <em class="rw-required">*</em></span>
                <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" required>
            </label>
            <label>
                <span>Email <em class="rw-required">*</em></span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required>
            </label>
            <label>
                <span>Phone <em class="rw-required">*</em></span>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Best contact number" required>
            </label>
            <label class="rw-form-full">
                <span>State <span class="rw-optional">(optional)</span></span>
                <select name="state">
                    <option value="">Select state</option>
                    @foreach (config('riskwisdom.states') as $value => $label)
                        <option value="{{ $value }}" @selected(old('state') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <p class="rw-form-trust">By downloading this guide, you agree to receive the guide by email and occasional home loan updates. You can unsubscribe anytime.</p>

        <button class="rw-button rw-button--solid" type="submit">{{ $guide['cta'] }}</button>
    </form>
@endsection
