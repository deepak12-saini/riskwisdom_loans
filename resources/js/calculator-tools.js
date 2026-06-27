const formatMoney = (value) => `$${Math.round(Number(value) || 0).toLocaleString('en-AU')}`;

const bindRangeSync = () => {
    document.querySelectorAll('[data-range-for]').forEach((range) => {
        const targetId = range.dataset.rangeFor;
        const input = document.getElementById(targetId) || document.querySelector(`[name="${targetId}"]`);

        if (!input) {
            return;
        }

        const syncFromInput = () => {
            const value = Number(input.value) || Number(range.min) || 0;
            range.value = String(Math.min(Number(range.max), Math.max(Number(range.min), value)));
        };

        const syncFromRange = () => {
            input.value = range.value;
            input.dispatchEvent(new Event('input', { bubbles: true }));
        };

        input.addEventListener('input', syncFromInput);
        input.addEventListener('change', syncFromInput);
        range.addEventListener('input', syncFromRange);

        syncFromInput();
    });
};

const bindToggleCards = () => {
    document.querySelectorAll('.rw-toggle-card input[type="checkbox"]').forEach((checkbox) => {
        const card = checkbox.closest('.rw-toggle-card');

        const sync = () => card?.classList.toggle('is-checked', checkbox.checked);

        checkbox.addEventListener('change', sync);
        sync();
    });
};

const bindRepaymentCalculator = () => {
    const root = document.getElementById('repayment-calculator');

    if (!root) {
        return;
    }

    const amountInput = document.getElementById('rc-amount');
    const rateInput = document.getElementById('rc-rate');
    const termInput = document.getElementById('rc-term');
    const result = document.getElementById('rc-result');
    const monthlyEl = document.getElementById('rc-monthly');
    const totalEl = document.getElementById('rc-total');
    const interestEl = document.getElementById('rc-interest');

    const calculate = () => {
        const principal = Number(amountInput?.value) || 0;
        const annualRate = Number(rateInput?.value) || 0;
        const years = Number(termInput?.value) || 30;
        const monthlyRate = annualRate / 100 / 12;
        const months = years * 12;

        let monthly = 0;

        if (monthlyRate === 0) {
            monthly = months > 0 ? principal / months : 0;
        } else {
            monthly = principal * (monthlyRate * Math.pow(1 + monthlyRate, months)) / (Math.pow(1 + monthlyRate, months) - 1);
        }

        const total = monthly * months;
        const interest = Math.max(0, total - principal);

        if (monthlyEl) {
            monthlyEl.textContent = formatMoney(monthly);
        }

        if (totalEl) {
            totalEl.textContent = formatMoney(total);
        }

        if (interestEl) {
            interestEl.textContent = formatMoney(interest);
        }

        if (result) {
            result.hidden = false;
            result.classList.add('is-visible');
        }
    };

    root.querySelectorAll('input').forEach((input) => {
        input.addEventListener('input', calculate);
        input.addEventListener('change', calculate);
    });

    root.querySelector('#rc-calculate')?.addEventListener('click', calculate);

    calculate();
};

const bindBorrowingSteps = () => {
    const unlock = document.getElementById('bp-unlock');
    const gate = document.getElementById('bp-lead-gate');
    const steps = document.querySelectorAll('.rw-calc-steps__item');

    unlock?.addEventListener('click', () => {
        if (gate) {
            gate.hidden = false;
            gate.classList.add('is-open');
            gate.querySelector('input')?.focus();
        }

        unlock.remove();
        steps[1]?.classList.add('is-active');
    });
};

export const initCalculatorTools = () => {
    bindRangeSync();
    bindToggleCards();
    bindRepaymentCalculator();
    bindBorrowingSteps();
};
