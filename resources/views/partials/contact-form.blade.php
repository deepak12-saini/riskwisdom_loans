@php
    $intent = request()->query('intent');
    $intentMap = config('riskwisdom.intent_map');
    $defaultLoanType = $intent && isset($intentMap[$intent]) ? $intentMap[$intent] : old('loan_type');
    $consultationBenefits = $consultationBenefits ?? ['Free consultation', 'Plan to move forward'];
@endphp

<div class="rw-contact__card">
    @if (session('status'))
        <div class="rw-form-alert rw-form-alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rw-form-alert rw-form-alert-error">
            <strong>We could not submit your enquiry yet.</strong>
            <ul class="rw-form-error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('contact.submit') }}" method="post" class="rw-form" id="contact-form">
        @csrf

        <input type="text" name="_gotcha" value="" tabindex="-1" autocomplete="off" class="rw-form-honeypot" aria-hidden="true">

        <input type="hidden" name="source" value="{{ old('source', $intent ?? '') }}">
        <input type="hidden" name="utm_source" value="{{ old('utm_source', request()->query('utm_source')) }}">
        <input type="hidden" name="utm_medium" value="{{ old('utm_medium', request()->query('utm_medium')) }}">
        <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign', request()->query('utm_campaign')) }}">

        <div class="rw-form-grid">
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

            <label @class(['is-invalid' => $errors->has('phone')])>
                <span>Phone <em class="rw-required">*</em></span>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone number" required>
                @error('phone')
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

            <label @class(['is-invalid' => $errors->has('loan_type')])>
                <span>Loan type <em class="rw-required">*</em></span>
                <select name="loan_type" required>
                    <option value="" disabled @selected(! $defaultLoanType) hidden>Select loan type</option>
                    @foreach (config('riskwisdom.loan_types') as $value => $label)
                        <option value="{{ $value }}" @selected(old('loan_type', $defaultLoanType) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('loan_type')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <label @class(['is-invalid' => $errors->has('timeline')])>
                <span>Timeline <em class="rw-required">*</em></span>
                <select name="timeline" required>
                    <option value="" disabled @selected(! old('timeline')) hidden>Select timeline</option>
                    @foreach (config('riskwisdom.timelines') as $value => $label)
                        <option value="{{ $value }}" @selected(old('timeline') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('timeline')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <label @class(['is-invalid' => $errors->has('state')])>
                <span>State <em class="rw-required">*</em></span>
                <select name="state" required>
                    <option value="" disabled @selected(! old('state')) hidden>Select state</option>
                    @foreach (config('riskwisdom.states') as $value => $label)
                        <option value="{{ $value }}" @selected(old('state') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('state')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <label class="rw-form-full @if ($errors->has('enquiry')) is-invalid @endif">
                <span>Enquiry <em class="rw-required">*</em></span>
                <textarea name="enquiry" rows="5" placeholder="Tell us about your finance goals" required>{{ old('enquiry') }}</textarea>
                @error('enquiry')
                    <small>{{ $message }}</small>
                @enderror
            </label>
        </div>

        <div class="rw-form-actions">
            <button class="rw-button rw-button--solid rw-button--wide" type="submit" data-cta="form-submit">Book a discovery call</button>
            @include('partials.phone-link', [
                'variant' => 'button',
                'label' => 'Call ' . config('riskwisdom.phone'),
                'cta' => 'form-call',
                'extraClass' => 'rw-button--wide',
                'wide' => true,
            ])
        </div>

        <p class="rw-form-trust">No obligation · Response within 24 hours · Australian borrowers only</p>
    </form>

    <div class="rw-contact__benefits">
        @foreach ($consultationBenefits as $benefit)
            <span>{{ $benefit }}</span>
        @endforeach
    </div>
</div>
