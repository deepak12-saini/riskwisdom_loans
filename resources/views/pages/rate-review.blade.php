@extends('layouts.site')

@section('title', 'Am I on the Right Rate? | Free Rate Review | Riskwisdom Loans')
@section('meta_description', 'Request a free home loan rate review. Tell us your current rate and a broker will call you back quickly to see if you could save on repayments.')
@section('canonical', route('rate-review'))
@section('body_class', 'rw-rate-review-page')
@section('header_class', 'rw-header--static')
@section('sticky_variant', 'call-only')

@section('content')
    <main class="rw-rate-review">
        <section class="rw-rate-review__hero">
            <div class="container rw-rate-review__hero-inner">
                <span class="rw-section-label">Rate review</span>
                <h1>Am I on the right rate?</h1>
                <p class="rw-rate-review__lead">
                    Share a few details and we will check whether your current home loan rate is still competitive — no obligation.
                </p>
                <p class="rw-rate-review__promise">
                    <span class="rw-rate-review__promise-icon" aria-hidden="true"></span>
                    {{ config('riskwisdom.rate_review.callback_promise') }}
                </p>
            </div>
        </section>

        <section class="rw-rate-review__main">
            <div class="container rw-rate-review__grid">
                <aside class="rw-rate-review__aside" aria-label="Why request a review">
                    <div class="rw-rate-review__card">
                        <h2>Why check your rate?</h2>
                        <ul class="rw-rate-review__list">
                            <li>
                                <span class="rw-rate-review__list-icon rw-rate-review__list-icon--chart" aria-hidden="true"></span>
                                <span>Compare your rate against current lender offers</span>
                            </li>
                            <li>
                                <span class="rw-rate-review__list-icon rw-rate-review__list-icon--clock" aria-hidden="true"></span>
                                <span>Quick phone review — no lengthy forms</span>
                            </li>
                            <li>
                                <span class="rw-rate-review__list-icon rw-rate-review__list-icon--shield" aria-hidden="true"></span>
                                <span>Clear advice on whether switching is worth it</span>
                            </li>
                        </ul>
                    </div>

                    <div class="rw-rate-review__card rw-rate-review__card--soft">
                        <h3>Prefer to talk now?</h3>
                        <p>Call us directly during business hours.</p>
                        @include('partials.phone-link', [
                            'variant' => 'button-solid',
                            'label' => config('riskwisdom.phone'),
                            'cta' => 'rate-review-phone',
                            'wide' => true,
                        ])
                    </div>
                </aside>

                <div class="rw-rate-review__panel" id="rate-review-form">
                    @if ($errors->any())
                        <div class="rw-form-alert rw-form-alert-error">
                            <strong>We could not submit your rate review yet.</strong>
                            <ul class="rw-form-error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('rate-review.submit') }}" method="post" class="rw-rate-review__form">
                        @csrf

                        <input type="text" name="_gotcha" value="" tabindex="-1" autocomplete="off" class="rw-form-honeypot" aria-hidden="true">
                        <input type="hidden" name="utm_source" value="{{ old('utm_source', request()->query('utm_source')) }}">
                        <input type="hidden" name="utm_medium" value="{{ old('utm_medium', request()->query('utm_medium')) }}">
                        <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign', request()->query('utm_campaign')) }}">

                        <h2>Request your free rate review</h2>
                        <p class="rw-rate-review__form-intro">Takes about 30 seconds. We will call the number you provide.</p>

                        <div class="rw-rate-review__fields">
                            <div class="rw-field @if ($errors->has('first_name')) is-invalid @endif">
                                <label class="rw-field__label" for="rr-first-name">First name</label>
                                <div class="rw-field__control">
                                    <input type="text" name="first_name" id="rr-first-name" value="{{ old('first_name') }}" placeholder="First name" required>
                                </div>
                                @error('first_name')
                                    <small class="rw-field__error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="rw-field @if ($errors->has('last_name')) is-invalid @endif">
                                <label class="rw-field__label" for="rr-last-name">Last name</label>
                                <div class="rw-field__control">
                                    <input type="text" name="last_name" id="rr-last-name" value="{{ old('last_name') }}" placeholder="Last name" required>
                                </div>
                                @error('last_name')
                                    <small class="rw-field__error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="rw-field @if ($errors->has('phone')) is-invalid @endif">
                                <label class="rw-field__label" for="rr-phone">Phone</label>
                                <div class="rw-field__control">
                                    <input type="text" name="phone" id="rr-phone" value="{{ old('phone') }}" placeholder="Best number to call you on" required>
                                </div>
                                @error('phone')
                                    <small class="rw-field__error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="rw-field @if ($errors->has('email')) is-invalid @endif">
                                <label class="rw-field__label" for="rr-email">Email</label>
                                <div class="rw-field__control">
                                    <input type="email" name="email" id="rr-email" value="{{ old('email') }}" placeholder="Email address" required>
                                </div>
                                @error('email')
                                    <small class="rw-field__error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="rw-field @if ($errors->has('current_rate')) is-invalid @endif">
                                <label class="rw-field__label" for="rr-current-rate">Current interest rate (% p.a.)</label>
                                <div class="rw-field__control">
                                    <input type="number" name="current_rate" id="rr-current-rate" min="0" max="20" step="0.01" value="{{ old('current_rate', '6.2') }}" placeholder="e.g. 6.24" required>
                                </div>
                                @error('current_rate')
                                    <small class="rw-field__error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="rw-field @if ($errors->has('loan_balance')) is-invalid @endif">
                                <label class="rw-field__label" for="rr-loan-balance">Approx. loan balance <span class="rw-optional">(optional)</span></label>
                                <div class="rw-field__control rw-field__control--money">
                                    <span class="rw-field__prefix">$</span>
                                    <input type="number" name="loan_balance" id="rr-loan-balance" min="0" step="1000" value="{{ old('loan_balance') }}" placeholder="e.g. 450000">
                                </div>
                                @error('loan_balance')
                                    <small class="rw-field__error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="rw-field rw-field--full @if ($errors->has('lender')) is-invalid @endif">
                                <label class="rw-field__label" for="rr-lender">Current lender <span class="rw-optional">(optional)</span></label>
                                <div class="rw-field__control">
                                    <input type="text" name="lender" id="rr-lender" value="{{ old('lender') }}" placeholder="e.g. CBA, Westpac, ANZ">
                                </div>
                                @error('lender')
                                    <small class="rw-field__error">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="rw-button rw-button--solid rw-button--wide" data-cta="rate-review-submit">
                            Request my free rate review
                        </button>
                        <p class="rw-form-trust">{{ config('riskwisdom.rate_review.callback_promise') }} · No obligation</p>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
