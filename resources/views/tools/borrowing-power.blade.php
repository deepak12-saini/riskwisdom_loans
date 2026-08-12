@extends('layouts.calculator', ['calculator' => 'borrowing-power'])

@section('title', 'Borrowing Power Calculator Australia | Riskwisdom Loans')
@section('meta_description', 'Free Australian borrowing power calculator. Estimate how much you could borrow for a home loan, then speak with a broker for an accurate assessment.')
@section('canonical', route('tools.borrowing-power'))

@section('calculator_intro')
    @php
        $result = session('borrowing_power_result');
        $showLeadGate = $errors->any() || session('borrowing_power_unlock') || $result;
    @endphp

    <span class="rw-section-label">Calculator</span>
    <h1>Borrowing power estimator</h1>
    <p class="rw-page-lead">
        This tool gives a rough guide only. Lenders apply detailed living expense benchmarks, credit policy, and buffers.
        Enter your finance details, then your contact information to see your guide range.
    </p>

    <ul class="rw-calculator-page__features">
        <li>Based on income, expenses, and deposit</li>
        <li>Shows a purchase price guide range</li>
        <li>Broker can refine against lender policy</li>
    </ul>

    @if ($result)
        <div class="rw-calc-result is-visible" id="bp-result" data-lead-conversion="borrowing_power">
            <span class="rw-calc-result__badge">Your guide range</span>
            <p class="rw-calc-result__value">{{ $result['range_label'] }}</p>
            <p class="rw-calc-result__label">
                Thanks {{ session('borrowing_power_submitted_first_name', 'for your details') }} — a broker can review your position for a more accurate assessment.
            </p>

            <div class="rw-calc-result__stats">
                <div class="rw-calc-result__stat">
                    <span>Monthly capacity</span>
                    <strong>${{ number_format($result['monthly_capacity']) }}</strong>
                </div>
                <div class="rw-calc-result__stat">
                    <span>Est. loan amount</span>
                    <strong>${{ number_format($result['loan_amount']) }}</strong>
                </div>
                <div class="rw-calc-result__stat">
                    <span>Deposit</span>
                    <strong>${{ number_format($result['deposit']) }}</strong>
                </div>
                <div class="rw-calc-result__stat">
                    <span>Rate / term</span>
                    <strong>{{ $result['rate'] }}% · {{ $result['term_years'] }} yrs</strong>
                </div>
            </div>

            <p class="rw-calc-result__disclaimer">
                Assumes ~30% of gross income available for repayments after expenses. Not a pre-approval or lender offer.
            </p>
        </div>
    @else
        <div class="rw-calculator-page__placeholder">
            <span class="rw-calculator-page__placeholder-icon" aria-hidden="true"></span>
            <p>Complete the form to unlock your estimated borrowing range here.</p>
        </div>
    @endif
@endsection

@section('calculator_panel')
    @php
        $showLeadGate = $errors->any() || session('borrowing_power_unlock') || session('borrowing_power_result');
    @endphp

    @if ($errors->any())
        <div class="rw-form-alert rw-form-alert-error">
            <strong>We could not show your estimate yet.</strong>
            <ul class="rw-form-error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('tools.borrowing-power.submit') }}"
        method="post"
        class="rw-calculator rw-calculator--interactive"
        id="borrowing-power-form"
        data-track-form="borrowing_power"
    >
        @csrf

        <input type="text" name="_gotcha" value="" tabindex="-1" autocomplete="off" class="rw-form-honeypot" aria-hidden="true">
        <input type="hidden" name="utm_source" value="{{ old('utm_source', request()->query('utm_source')) }}">
        <input type="hidden" name="utm_medium" value="{{ old('utm_medium', request()->query('utm_medium')) }}">
        <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign', request()->query('utm_campaign')) }}">

        <ol class="rw-calc-steps" aria-label="Calculator steps">
            <li class="rw-calc-steps__item is-active">Finance details</li>
            <li @class(['rw-calc-steps__item', 'is-active' => $showLeadGate])>Your details</li>
        </ol>

        <fieldset class="rw-calculator__fieldset">
            <legend class="rw-calculator__legend">Your finance inputs</legend>

            <div class="rw-calculator__stack">
                <div class="rw-field @if ($errors->has('income')) is-invalid @endif">
                    <label class="rw-field__label" for="bp-income">Annual gross income (before tax)</label>
                    <div class="rw-field__control rw-field__control--money">
                        <span class="rw-field__prefix">$</span>
                        <input type="number" name="income" id="bp-income" min="30000" max="2000000" step="1000" value="{{ old('income', 120000) }}" required>
                    </div>
                    <input type="range" class="rw-field__range" data-range-for="bp-income" data-range-format="money" data-range-max-plus="1" min="30000" max="500000" step="5000" value="{{ old('income', 120000) }}" aria-label="Adjust annual income">
                </div>

                <div class="rw-field @if ($errors->has('expenses')) is-invalid @endif">
                    <label class="rw-field__label" for="bp-expenses">Monthly living expenses</label>
                    <div class="rw-field__control rw-field__control--money">
                        <span class="rw-field__prefix">$</span>
                        <input type="number" name="expenses" id="bp-expenses" min="0" max="20000" step="100" value="{{ old('expenses', 3500) }}" required>
                    </div>
                    <input type="range" class="rw-field__range" data-range-for="bp-expenses" data-range-format="money" min="500" max="15000" step="100" value="{{ old('expenses', 3500) }}" aria-label="Adjust monthly expenses">
                </div>

                <div class="rw-field @if ($errors->has('deposit')) is-invalid @endif">
                    <label class="rw-field__label" for="bp-deposit">Deposit available</label>
                    <div class="rw-field__control rw-field__control--money">
                        <span class="rw-field__prefix">$</span>
                        <input type="number" name="deposit" id="bp-deposit" min="0" max="3000000" step="1000" value="{{ old('deposit', 80000) }}" required>
                    </div>
                    <input type="range" class="rw-field__range" data-range-for="bp-deposit" data-range-format="money" data-range-max-plus="1" min="0" max="500000" step="5000" value="{{ old('deposit', 80000) }}" aria-label="Adjust deposit">
                </div>

                <div class="rw-calculator__grid rw-calculator__grid--compact">
                    <div class="rw-field @if ($errors->has('rate')) is-invalid @endif">
                        <label class="rw-field__label" for="bp-rate">Interest rate (% p.a.)</label>
                        <div class="rw-field__control">
                            <input type="number" name="rate" id="bp-rate" min="0" max="15" step="0.1" value="{{ old('rate', 6.2) }}" required>
                        </div>
                        <input type="range" class="rw-field__range" data-range-for="bp-rate" data-range-format="percent" min="3" max="12" step="0.1" value="{{ old('rate', 6.2) }}" aria-label="Adjust interest rate">
                    </div>

                    <div class="rw-field @if ($errors->has('term')) is-invalid @endif">
                        <label class="rw-field__label" for="bp-term">Loan term (years)</label>
                        <div class="rw-field__control">
                            <input type="number" name="term" id="bp-term" min="5" max="30" step="1" value="{{ old('term', 30) }}" required>
                        </div>
                        <input type="range" class="rw-field__range" data-range-for="bp-term" data-range-format="years" min="5" max="30" step="1" value="{{ old('term', 30) }}" aria-label="Adjust loan term">
                    </div>
                </div>
            </div>
        </fieldset>

        <div
            class="rw-calculator__gate @if ($showLeadGate) is-open @endif"
            id="bp-lead-gate"
            @if (! $showLeadGate) hidden @endif
        >
            <h2>See your estimated range</h2>
            <p>Enter your name, email, and phone to view your borrowing guide. No obligation — we will only follow up if you would like broker help.</p>

            <div class="rw-calculator__grid">
                <div class="rw-field @if ($errors->has('first_name')) is-invalid @endif">
                    <label class="rw-field__label" for="bp-first-name">First name</label>
                    <div class="rw-field__control">
                        <input type="text" name="first_name" id="bp-first-name" value="{{ old('first_name') }}" placeholder="First name" required>
                    </div>
                    @error('first_name')
                        <small class="rw-field__error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="rw-field @if ($errors->has('last_name')) is-invalid @endif">
                    <label class="rw-field__label" for="bp-last-name">Last name</label>
                    <div class="rw-field__control">
                        <input type="text" name="last_name" id="bp-last-name" value="{{ old('last_name') }}" placeholder="Last name" required>
                    </div>
                    @error('last_name')
                        <small class="rw-field__error">{{ $message }}</small>
                    @enderror
                </div>

                @include('partials.phone-field', [
                    'id' => 'bp-phone',
                    'fullWidth' => true,
                    'placeholder' => 'Phone number',
                ])

                <div class="rw-field rw-form-full @if ($errors->has('email')) is-invalid @endif">
                    <label class="rw-field__label" for="bp-email">Email</label>
                    <div class="rw-field__control">
                        <input type="email" name="email" id="bp-email" value="{{ old('email') }}" placeholder="Email address" required>
                    </div>
                    @error('email')
                        <small class="rw-field__error">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="rw-form-full">
                @include('partials.marketing-consent')
            </div>

            <button
                type="submit"
                class="rw-button rw-button--solid rw-button--wide rw-calculator__submit"
                data-cta="borrowing-power-submit"
                data-loading-text="Calculating…"
            >
                Show my estimate
            </button>
            <p class="rw-form-trust">Assumes ~30% of gross income available for repayments after expenses. Not a pre-approval or lender offer.</p>
        </div>

        @unless ($showLeadGate)
            <button type="button" class="rw-button rw-button--solid rw-button--wide rw-calculator__submit" id="bp-unlock" data-cta="borrowing-power-unlock">
                Continue to my estimate
            </button>
        @endunless
    </form>
@endsection

@section('calculator_footer')
    <div class="rw-page-cta-band rw-calculator-page__cta">
        <h2>Want an accurate borrowing assessment?</h2>
        <p>Book a free call or speak with a broker about your position against current lender policy.</p>
        <div class="rw-page-actions">
            <a class="rw-button rw-button--solid" href="{{ route('book') }}" data-cta="calculator-borrowing-book">Book a call</a>
            <a class="rw-button rw-button--outline" href="{{ contact_url('home_loans') }}" data-cta="calculator-borrowing-contact">Get accurate assessment</a>
            @include('partials.phone-link', [
                'variant' => 'button',
                'label' => 'Call ' . config('riskwisdom.phone'),
                'cta' => 'calculator-borrowing-phone',
            ])
        </div>
    </div>
@endsection
