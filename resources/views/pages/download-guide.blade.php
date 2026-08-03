@extends('layouts.site')

@section('title', $guide['title'].' | Riskwisdom Loans')
@section('meta_description', $guide['description'])
@section('canonical', route('guides.download.show', $slug))
@section('header_class', 'rw-header--static')
@section('body_class', 'rw-page-download-guide')

@section('content')
    <main class="rw-download-guide">
        <section class="rw-download-guide__hero">
            <div class="rw-download-guide__hero-inner">
                <div class="rw-download-guide__story">
                    <span class="rw-download-guide__label">Free downloadable guide</span>
                    <h1>{{ $guide['heading'] }}</h1>
                    <p class="rw-download-guide__lead">{{ $guide['description'] }}</p>

                    <ul class="rw-download-guide__benefits">
                        <li>
                            <span class="rw-download-guide__benefit-icon" aria-hidden="true">1</span>
                            <div>
                                <strong>Instant access</strong>
                                <p>Download on the thank-you page as soon as you submit.</p>
                            </div>
                        </li>
                        <li>
                            <span class="rw-download-guide__benefit-icon" aria-hidden="true">2</span>
                            <div>
                                <strong>Email copy</strong>
                                <p>We also send the guide to your inbox for later.</p>
                            </div>
                        </li>
                        <li>
                            <span class="rw-download-guide__benefit-icon" aria-hidden="true">3</span>
                            <div>
                                <strong>Practical next steps</strong>
                                <p>Clear actions you can review in your own time.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="rw-download-guide__media">
                    <img
                        src="{{ asset($guide['image'] ?? 'images/landing/home-loans-advisor.jpg') }}"
                        alt="{{ $guide['image_alt'] ?? $guide['title'] }}"
                        width="720"
                        height="520"
                        loading="eager"
                        decoding="async"
                    >
                </div>
            </div>
        </section>

        <section class="rw-download-guide__form-section" aria-labelledby="guide-form-heading">
            <div class="rw-download-guide__form-wrap">
                <form
                    action="{{ route('guides.download.store', $slug) }}"
                    method="post"
                    class="rw-download-guide__form"
                    id="guide-download-form"
                    data-submit-loader-form
                >
                    @csrf
                    <input type="text" name="_gotcha" value="" tabindex="-1" autocomplete="off" class="rw-form-honeypot" aria-hidden="true">

                    @if ($errors->any())
                        <div class="rw-form-alert rw-form-alert-error">
                            <strong>We could not send your guide yet.</strong>
                            <p>Please check the highlighted fields below.</p>
                        </div>
                    @endif

                    <div class="rw-download-guide__form-intro">
                        <h2 id="guide-form-heading">Send the guide to my inbox</h2>
                        <p>Enter your details for instant access now and a copy by email.</p>
                    </div>

                    <div class="rw-download-guide__grid">
                        <label class="@if ($errors->has('first_name')) is-invalid @endif">
                            <span>First name <em class="rw-required">*</em></span>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required autocomplete="given-name">
                            @error('first_name')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>
                        <label class="@if ($errors->has('last_name')) is-invalid @endif">
                            <span>Last name <em class="rw-required">*</em></span>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" required autocomplete="family-name">
                            @error('last_name')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>
                        @include('partials.phone-field', [
                            'variant' => 'label',
                            'fullWidth' => true,
                            'placeholder' => 'Phone number',
                        ])
                        <label class="rw-download-guide__full @if ($errors->has('email')) is-invalid @endif">
                            <span>Email <em class="rw-required">*</em></span>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required autocomplete="email">
                            @error('email')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>
                        <label class="rw-download-guide__full @if ($errors->has('state')) is-invalid @endif">
                            <span>State <span class="rw-optional">(optional)</span></span>
                            <select name="state">
                                <option value="">Select state</option>
                                @foreach (config('riskwisdom.states') as $value => $label)
                                    <option value="{{ $value }}" @selected(old('state') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('state')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>
                    </div>

                    <p class="rw-download-guide__trust">
                        By downloading this guide, you agree to receive the guide by email and occasional home loan updates. You can unsubscribe anytime.
                    </p>

                    <button
                        class="rw-button rw-button--solid rw-download-guide__submit"
                        type="submit"
                        data-loading-text="Sending your guide…"
                    >
                        {{ $guide['cta'] }}
                    </button>
                </form>
            </div>
        </section>
    </main>
@endsection
