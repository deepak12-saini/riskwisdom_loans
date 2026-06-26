@extends('layouts.page')

@section('title', 'Home Loan Repayment Calculator | Riskwisdom Loans')
@section('meta_description', 'Calculate estimated monthly home loan repayments for Australian mortgages. Adjust loan amount, rate, and term.')
@section('canonical', route('tools.repayment-calculator'))

@section('page_content')
    <span class="rw-section-label">Calculator</span>
    <h1>Repayment calculator</h1>
    <p class="rw-page-lead">
        Model principal and interest repayments across different loan amounts, rates, and terms.
    </p>

    <div class="rw-calculator" id="repayment-calculator">
        <div class="rw-calculator__grid">
            <label>
                <span>Loan amount</span>
                <input type="number" id="rc-amount" min="0" step="1000" value="500000">
            </label>
            <label>
                <span>Interest rate (% p.a.)</span>
                <input type="number" id="rc-rate" min="0" max="20" step="0.1" value="6.2">
            </label>
            <label>
                <span>Loan term (years)</span>
                <input type="number" id="rc-term" min="1" max="30" step="1" value="30">
            </label>
        </div>

        <button type="button" class="rw-button rw-button--solid" id="rc-calculate">Calculate repayment</button>

        <div class="rw-calculator__result" id="rc-result" hidden>
            <h2>Estimated monthly repayment</h2>
            <p class="rw-calculator__amount" id="rc-monthly"></p>
            <p class="rw-calculator__note" id="rc-total"></p>
        </div>
    </div>

    <div class="rw-page-cta-band">
        <h2>Compare refinance or purchase options</h2>
        <p>See how a different rate or structure could affect your repayments with broker guidance.</p>
        <a class="rw-button rw-button--solid" href="{{ contact_url('refinance') }}" data-cta="calculator-repayment">Get free loan review</a>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('rc-calculate')?.addEventListener('click', () => {
            const principal = Number(document.getElementById('rc-amount').value) || 0;
            const annualRate = Number(document.getElementById('rc-rate').value) || 0;
            const years = Number(document.getElementById('rc-term').value) || 30;
            const monthlyRate = annualRate / 100 / 12;
            const months = years * 12;

            let monthly = 0;
            if (monthlyRate === 0) {
                monthly = principal / months;
            } else {
                monthly = principal * (monthlyRate * Math.pow(1 + monthlyRate, months)) / (Math.pow(1 + monthlyRate, months) - 1);
            }

            const total = monthly * months;

            document.getElementById('rc-monthly').textContent = `$${Math.round(monthly).toLocaleString()} per month`;
            document.getElementById('rc-total').textContent = `Total repayments over ${years} years: $${Math.round(total).toLocaleString()} (principal & interest)`;
            document.getElementById('rc-result').hidden = false;
        });
    </script>
@endpush
