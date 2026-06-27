@extends('layouts.calculator', ['calculator' => 'repayment'])

@section('title', 'Home Loan Repayment Calculator | Riskwisdom Loans')
@section('meta_description', 'Calculate estimated monthly home loan repayments for Australian mortgages. Adjust loan amount, rate, and term.')
@section('canonical', route('tools.repayment-calculator'))

@section('calculator_intro')
    <span class="rw-section-label">Calculator</span>
    <h1>Repayment calculator</h1>
    <p class="rw-page-lead">
        Model principal and interest repayments across different loan amounts, rates, and terms.
        Results update instantly as you adjust the sliders.
    </p>

    <ul class="rw-calculator-page__features">
        <li>Live updates as you change inputs</li>
        <li>Principal and interest modelling</li>
        <li>Compare rate and term scenarios quickly</li>
    </ul>

    <div class="rw-calc-result is-visible" id="rc-result" hidden>
        <span class="rw-calc-result__badge">Live estimate</span>
        <p class="rw-calc-result__value" id="rc-monthly">$0</p>
        <p class="rw-calc-result__label">Estimated monthly repayment</p>

        <div class="rw-calc-result__stats">
            <div class="rw-calc-result__stat">
                <span>Total repayments</span>
                <strong id="rc-total">$0</strong>
            </div>
            <div class="rw-calc-result__stat">
                <span>Total interest</span>
                <strong id="rc-interest">$0</strong>
            </div>
        </div>

        <p class="rw-calc-result__disclaimer">
            Fixed-rate P&amp;I guide only — does not include fees, offset accounts, or rate changes.
        </p>
    </div>
@endsection

@section('calculator_panel')
    <div class="rw-calculator rw-calculator--interactive" id="repayment-calculator">
        <div class="rw-calculator__stack">
            <div class="rw-field">
                <label class="rw-field__label" for="rc-amount">Loan amount</label>
                <div class="rw-field__control rw-field__control--money">
                    <span class="rw-field__prefix">$</span>
                    <input type="number" id="rc-amount" min="50000" max="3000000" step="1000" value="500000">
                </div>
                <input type="range" class="rw-field__range" data-range-for="rc-amount" min="50000" max="2000000" step="10000" value="500000" aria-label="Adjust loan amount">
            </div>

            <div class="rw-field">
                <label class="rw-field__label" for="rc-rate">Interest rate (% p.a.)</label>
                <div class="rw-field__control">
                    <input type="number" id="rc-rate" min="0" max="20" step="0.1" value="6.2">
                </div>
                <input type="range" class="rw-field__range" data-range-for="rc-rate" min="2" max="12" step="0.1" value="6.2" aria-label="Adjust interest rate">
            </div>

            <div class="rw-field">
                <label class="rw-field__label" for="rc-term">Loan term (years)</label>
                <div class="rw-field__control">
                    <input type="number" id="rc-term" min="1" max="30" step="1" value="30">
                </div>
                <input type="range" class="rw-field__range" data-range-for="rc-term" min="5" max="30" step="1" value="30" aria-label="Adjust loan term">
            </div>
        </div>

        <button type="button" class="rw-button rw-button--solid rw-button--wide rw-calculator__submit" id="rc-calculate">
            Recalculate repayment
        </button>
        <p class="rw-form-trust">Principal &amp; interest · Guide estimate only</p>
    </div>
@endsection

@section('calculator_footer')
    <div class="rw-page-cta-band rw-calculator-page__cta">
        <h2>Compare refinance or purchase options</h2>
        <p>See how a different rate or structure could affect your repayments with broker guidance.</p>
        <div class="rw-page-actions">
            <a class="rw-button rw-button--solid" href="{{ rate_review_url() }}" data-cta="calculator-repayment">Get free loan review</a>
            @include('partials.phone-link', [
                'variant' => 'button',
                'label' => 'Call ' . config('riskwisdom.phone'),
                'cta' => 'calculator-repayment-phone',
            ])
        </div>
    </div>
@endsection
