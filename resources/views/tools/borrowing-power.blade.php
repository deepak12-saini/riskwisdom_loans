@extends('layouts.page')

@section('title', 'Borrowing Power Calculator | Riskwisdom Loans')
@section('meta_description', 'Estimate your borrowing capacity with this simple Australian home loan calculator. Get a guide range, then speak with a broker for an accurate assessment.')
@section('canonical', route('tools.borrowing-power'))

@section('page_content')
    <span class="rw-section-label">Calculator</span>
    <h1>Borrowing power estimator</h1>
    <p class="rw-page-lead">
        This tool gives a rough guide only. Lenders apply detailed living expense benchmarks, credit policy, and buffers.
        For an accurate assessment, book a free consultation.
    </p>

    <div class="rw-calculator" id="borrowing-power-calculator">
        <div class="rw-calculator__grid">
            <label>
                <span>Annual gross income (before tax)</span>
                <input type="number" id="bp-income" min="0" step="1000" value="120000">
            </label>
            <label>
                <span>Monthly living expenses</span>
                <input type="number" id="bp-expenses" min="0" step="100" value="3500">
            </label>
            <label>
                <span>Deposit available</span>
                <input type="number" id="bp-deposit" min="0" step="1000" value="80000">
            </label>
            <label>
                <span>Estimated interest rate (%)</span>
                <input type="number" id="bp-rate" min="1" max="15" step="0.1" value="6.2">
            </label>
            <label>
                <span>Loan term (years)</span>
                <input type="number" id="bp-term" min="5" max="30" step="1" value="30">
            </label>
        </div>

        <button type="button" class="rw-button rw-button--solid" id="bp-calculate">Estimate borrowing range</button>

        <div class="rw-calculator__result" id="bp-result" hidden>
            <h2>Estimated guide range</h2>
            <p class="rw-calculator__amount" id="bp-amount"></p>
            <p class="rw-calculator__note">Assumes ~30% of gross income available for repayments after expenses. Not a pre-approval or lender offer.</p>
        </div>
    </div>

    <div class="rw-page-cta-band">
        <h2>Want an accurate borrowing assessment?</h2>
        <p>Share your details and a broker will review your position against current lender policy.</p>
        <a class="rw-button rw-button--solid" href="{{ contact_url('home_loans') }}" data-cta="calculator-borrowing">Get accurate assessment</a>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('bp-calculate')?.addEventListener('click', () => {
            const income = Number(document.getElementById('bp-income').value) || 0;
            const expenses = Number(document.getElementById('bp-expenses').value) || 0;
            const deposit = Number(document.getElementById('bp-deposit').value) || 0;
            const rate = (Number(document.getElementById('bp-rate').value) || 6) / 100 / 12;
            const termMonths = (Number(document.getElementById('bp-term').value) || 30) * 12;

            const monthlyCapacity = Math.max(0, (income / 12) * 0.3 - expenses);
            let loanAmount = 0;

            if (rate > 0) {
                loanAmount = monthlyCapacity * ((1 - Math.pow(1 + rate, -termMonths)) / rate);
            } else {
                loanAmount = monthlyCapacity * termMonths;
            }

            const low = Math.max(0, Math.round((loanAmount + deposit) * 0.9 / 1000) * 1000);
            const high = Math.max(0, Math.round((loanAmount + deposit) * 1.05 / 1000) * 1000);

            document.getElementById('bp-amount').textContent = `$${low.toLocaleString()} – $${high.toLocaleString()} (purchase price guide)`;
            document.getElementById('bp-result').hidden = false;
        });
    </script>
@endpush
