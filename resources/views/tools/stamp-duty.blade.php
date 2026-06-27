@extends('layouts.calculator', ['calculator' => 'stamp-duty'])

@section('title', 'Stamp Duty Calculator | Riskwisdom Loans')
@section('meta_description', 'Estimate stamp duty and government charges for property purchases across Australian states and territories. Guide only — confirm with your broker or conveyancer.')
@section('canonical', route('tools.stamp-duty'))

@section('calculator_intro')
    @php
        $result = session('stamp_duty_result');
    @endphp

    <span class="rw-section-label">Calculator</span>
    <h1>Stamp duty estimator</h1>
    <p class="rw-page-lead">
        Estimate transfer duty and basic government charges when buying property in Australia.
        Figures are a guide only — concessions, surcharges, and eligibility rules vary by state.
    </p>

    <ul class="rw-calculator-page__features">
        <li>All states and territories supported</li>
        <li>First home buyer concession toggle</li>
        <li>Instant guide estimate in seconds</li>
    </ul>

    @if ($result)
        <div class="rw-calc-result is-visible" id="sd-result">
            <span class="rw-calc-result__badge">Your estimate</span>
            <p class="rw-calc-result__value">${{ number_format($result['total_government_charges']) }}</p>
            <p class="rw-calc-result__label">Estimated total government charges</p>

            <div class="rw-calc-result__stats">
                <div class="rw-calc-result__stat">
                    <span>Stamp duty</span>
                    <strong>${{ number_format($result['duty']) }}</strong>
                </div>
                <div class="rw-calc-result__stat">
                    <span>Mortgage registration</span>
                    <strong>${{ number_format($result['mortgage_registration_fee']) }}</strong>
                </div>
                <div class="rw-calc-result__stat">
                    <span>State</span>
                    <strong>{{ $result['state_label'] }}</strong>
                </div>
                <div class="rw-calc-result__stat">
                    <span>Purchase price</span>
                    <strong>${{ number_format($result['property_value']) }}</strong>
                </div>
            </div>

            @if ($result['fhb_note'])
                <p class="rw-calc-result__note">{{ $result['fhb_note'] }}</p>
            @endif

            <p class="rw-calc-result__disclaimer">
                Guide only — confirm with your broker or conveyancer before making an offer.
            </p>
        </div>
    @else
        <div class="rw-calculator-page__placeholder">
            <span class="rw-calculator-page__placeholder-icon" aria-hidden="true"></span>
            <p>Adjust the inputs and calculate to see your stamp duty estimate here.</p>
        </div>
    @endif
@endsection

@section('calculator_panel')
    @if ($errors->any())
        <div class="rw-form-alert rw-form-alert-error">
            <strong>We could not calculate your estimate yet.</strong>
            <ul class="rw-form-error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('tools.stamp-duty.calculate') }}"
        method="post"
        class="rw-calculator rw-calculator--interactive"
        id="sd-calculator"
    >
        @csrf

        <div class="rw-field">
            <label class="rw-field__label" for="sd-state">State or territory</label>
            <div class="rw-field__control">
                <select name="state" id="sd-state" required>
                    <option value="" disabled @selected(! old('state')) hidden>Select state</option>
                    @foreach (config('riskwisdom.states') as $code => $label)
                        <option value="{{ $code }}" @selected(old('state', 'NSW') === $code)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @error('state')
                <small class="rw-field__error">{{ $message }}</small>
            @enderror
        </div>

        <div class="rw-field @if ($errors->has('property_value')) is-invalid @endif">
            <label class="rw-field__label" for="sd-property-value">Property purchase price</label>
            <div class="rw-field__control rw-field__control--money">
                <span class="rw-field__prefix">$</span>
                <input
                    type="number"
                    name="property_value"
                    id="sd-property-value"
                    min="50000"
                    max="5000000"
                    step="1000"
                    value="{{ old('property_value', 650000) }}"
                    required
                >
            </div>
            <input
                type="range"
                class="rw-field__range"
                data-range-for="sd-property-value"
                min="50000"
                max="5000000"
                step="10000"
                value="{{ old('property_value', 650000) }}"
                aria-label="Adjust property purchase price"
            >
            @error('property_value')
                <small class="rw-field__error">{{ $message }}</small>
            @enderror
        </div>

        <label class="rw-toggle-card @if(old('first_home_buyer')) is-checked @endif">
            <input
                type="checkbox"
                name="first_home_buyer"
                value="1"
                @checked(old('first_home_buyer'))
            >
            <span class="rw-toggle-card__content">
                <span class="rw-toggle-card__copy">
                    <strong>First home buyer</strong>
                    <small>Apply concession if eligible in your state</small>
                </span>
                <span class="rw-toggle-card__switch" aria-hidden="true"></span>
            </span>
        </label>

        <button type="submit" class="rw-button rw-button--solid rw-button--wide rw-calculator__submit" data-cta="stamp-duty-calculate">
            Calculate stamp duty
        </button>
        <p class="rw-form-trust">Guide estimate only · Does not include transfer fees, legal costs, or LMI</p>
    </form>
@endsection

@section('calculator_footer')
    <div class="rw-page-cta-band rw-calculator-page__cta">
        <h2>Planning a purchase?</h2>
        <p>Speak with a broker about stamp duty, grants, and your total upfront costs before you make an offer.</p>
        <div class="rw-page-actions">
            <a class="rw-button rw-button--solid" href="{{ route('book') }}" data-cta="stamp-duty-book">Book a call</a>
            <a class="rw-button rw-button--outline" href="{{ contact_url('home_loans') }}" data-cta="stamp-duty-contact">Get purchase advice</a>
            @include('partials.phone-link', [
                'variant' => 'button',
                'label' => 'Call ' . config('riskwisdom.phone'),
                'cta' => 'stamp-duty-phone',
            ])
        </div>
    </div>
@endsection
