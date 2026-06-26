@extends('layouts.page')

@section('title', 'Borrowing Power Calculator | Riskwisdom Loans')
@section('meta_description', 'Estimate your borrowing capacity with this simple Australian home loan calculator. Enter your details to see your guide range, then speak with a broker for an accurate assessment.')
@section('canonical', route('tools.borrowing-power'))

@section('page_content')
    @php
        $result = session('borrowing_power_result');
        $showLeadGate = $errors->any() || session('borrowing_power_unlock') || $result;
    @endphp

    <span class="rw-section-label">Calculator</span>
    <h1>Borrowing power estimator</h1>
    <p class="rw-page-lead">
        This tool gives a rough guide only. Lenders apply detailed living expense benchmarks, credit policy, and buffers.
        Enter your finance details below, then your contact information to see your guide range.
    </p>

    @if ($result)
        <div class="rw-calculator__result" id="bp-result">
            <h2>Your estimated guide range</h2>
            <p class="rw-calculator__amount">{{ $result['range_label'] }}</p>
            <p class="rw-calculator__note">
                Thanks {{ session('borrowing_power_submitted_first_name', 'for your details') }} — a broker can review your position against current lender policy for a more accurate assessment.
            </p>
        </div>
    @endif

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
        class="rw-calculator"
        id="borrowing-power-form"
    >
        @csrf

        <input type="text" name="_gotcha" value="" tabindex="-1" autocomplete="off" class="rw-form-honeypot" aria-hidden="true">
        <input type="hidden" name="utm_source" value="{{ old('utm_source', request()->query('utm_source')) }}">
        <input type="hidden" name="utm_medium" value="{{ old('utm_medium', request()->query('utm_medium')) }}">
        <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign', request()->query('utm_campaign')) }}">

        <fieldset class="rw-calculator__fieldset">
            <legend class="rw-calculator__legend">Your finance inputs</legend>
            <div class="rw-calculator__grid">
                <label @class(['is-invalid' => $errors->has('income')])>
                    <span>Annual gross income (before tax)</span>
                    <input type="number" name="income" id="bp-income" min="0" step="1000" value="{{ old('income', 120000) }}" required>
                </label>
                <label @class(['is-invalid' => $errors->has('expenses')])>
                    <span>Monthly living expenses</span>
                    <input type="number" name="expenses" id="bp-expenses" min="0" step="100" value="{{ old('expenses', 3500) }}" required>
                </label>
                <label @class(['is-invalid' => $errors->has('deposit')])>
                    <span>Deposit available</span>
                    <input type="number" name="deposit" id="bp-deposit" min="0" step="1000" value="{{ old('deposit', 80000) }}" required>
                </label>
                <label @class(['is-invalid' => $errors->has('rate')])>
                    <span>Estimated interest rate (%)</span>
                    <input type="number" name="rate" id="bp-rate" min="0" max="15" step="0.1" value="{{ old('rate', 6.2) }}" required>
                </label>
                <label @class(['is-invalid' => $errors->has('term')])>
                    <span>Loan term (years)</span>
                    <input type="number" name="term" id="bp-term" min="5" max="30" step="1" value="{{ old('term', 30) }}" required>
                </label>
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
                <label @class(['is-invalid' => $errors->has('first_name')])>
                    <span>First name <em class="rw-required">*</em></span>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required>
                    @error('first_name')
                        <small>{{ $message }}</small>
                    @enderror
                </label>

                <label @class(['is-invalid' => $errors->has('last_name')])>
                    <span>Last name <em class="rw-required">*</em></span>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" required>
                    @error('last_name')
                        <small>{{ $message }}</small>
                    @enderror
                </label>

                <label @class(['is-invalid' => $errors->has('email')])>
                    <span>Email <em class="rw-required">*</em></span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required>
                    @error('email')
                        <small>{{ $message }}</small>
                    @enderror
                </label>

                <label @class(['is-invalid' => $errors->has('phone')])>
                    <span>Phone <em class="rw-required">*</em></span>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone number" required>
                    @error('phone')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
            </div>

            <button type="submit" class="rw-button rw-button--solid" data-cta="borrowing-power-submit">
                Show my estimate
            </button>
            <p class="rw-form-trust">Assumes ~30% of gross income available for repayments after expenses. Not a pre-approval or lender offer.</p>
        </div>

        @unless ($showLeadGate)
            <button type="button" class="rw-button rw-button--solid" id="bp-unlock" data-cta="borrowing-power-unlock">
                Continue to my estimate
            </button>
        @endunless
    </form>

    <div class="rw-page-cta-band">
        <h2>Want an accurate borrowing assessment?</h2>
        <p>Book a free call or speak with a broker about your position against current lender policy.</p>
        <div class="rw-page-actions">
            <a class="rw-button rw-button--solid" href="{{ route('book') }}" data-cta="calculator-borrowing-book">Book a call</a>
            <a class="rw-button rw-button--outline" href="{{ contact_url('home_loans') }}" data-cta="calculator-borrowing-contact">Get accurate assessment</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('bp-unlock')?.addEventListener('click', () => {
            const gate = document.getElementById('bp-lead-gate');
            const unlock = document.getElementById('bp-unlock');

            if (gate) {
                gate.hidden = false;
                gate.classList.add('is-open');
                gate.querySelector('input')?.focus();
            }

            unlock?.remove();
        });
    </script>
@endpush
