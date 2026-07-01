@php
    $formAction = $campaign === 'default'
        ? route('enquire.submit')
        : route('enquire.campaign.submit', ['campaign' => $campaign]);
    $defaultLoanType = old('loan_type', $landing['default_loan_type'] ?? null);
    $formIdPrefix = 'cv-'.$campaign;
@endphp

<div class="rw-conversion-form" id="enquiry-form">
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

    <form action="{{ $formAction }}" method="post" class="rw-conversion-form__inner" data-track-form="conversion">
        @csrf

        <input type="text" name="_gotcha" value="" tabindex="-1" autocomplete="off" class="rw-form-honeypot" aria-hidden="true">
        <input type="hidden" name="utm_source" value="{{ old('utm_source', request()->query('utm_source')) }}">
        <input type="hidden" name="utm_medium" value="{{ old('utm_medium', request()->query('utm_medium')) }}">
        <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign', request()->query('utm_campaign')) }}">

        <div class="rw-conversion-form__top">
            <span class="rw-conversion-form__pill">Free · No obligation</span>
            <div class="rw-conversion-form__header">
                <h2>{{ $landing['form_headline'] }}</h2>
                <p>{{ $landing['form_intro'] }}</p>
            </div>
        </div>

        <div class="rw-conversion-form__fields">
            <div class="rw-conversion-form__section">
                <p class="rw-conversion-form__section-label">Your details</p>

                <div class="rw-conversion-form__row">
                    <div class="rw-field @if ($errors->has('first_name')) is-invalid @endif">
                        <label class="rw-field__label" for="{{ $formIdPrefix }}-first-name">First name</label>
                        <div class="rw-field__control">
                            <input type="text" name="first_name" id="{{ $formIdPrefix }}-first-name" value="{{ old('first_name') }}" placeholder="Jane" required autocomplete="given-name">
                        </div>
                        @error('first_name')
                            <small class="rw-field__error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="rw-field @if ($errors->has('last_name')) is-invalid @endif">
                        <label class="rw-field__label" for="{{ $formIdPrefix }}-last-name">Last name</label>
                        <div class="rw-field__control">
                            <input type="text" name="last_name" id="{{ $formIdPrefix }}-last-name" value="{{ old('last_name') }}" placeholder="Smith" required autocomplete="family-name">
                        </div>
                        @error('last_name')
                            <small class="rw-field__error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="rw-field @if ($errors->has('phone')) is-invalid @endif">
                    <label class="rw-field__label" for="{{ $formIdPrefix }}-phone">Phone</label>
                    <div class="rw-field__control">
                        <input type="tel" name="phone" id="{{ $formIdPrefix }}-phone" value="{{ old('phone') }}" placeholder="04xx xxx xxx" required autocomplete="tel">
                    </div>
                    @error('phone')
                        <small class="rw-field__error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="rw-field @if ($errors->has('email')) is-invalid @endif">
                    <label class="rw-field__label" for="{{ $formIdPrefix }}-email">Email</label>
                    <div class="rw-field__control">
                        <input type="email" name="email" id="{{ $formIdPrefix }}-email" value="{{ old('email') }}" placeholder="you@email.com" required autocomplete="email">
                    </div>
                    @error('email')
                        <small class="rw-field__error">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="rw-conversion-form__section">
                <p class="rw-conversion-form__section-label">What you are looking for</p>

                <div class="rw-field @if ($errors->has('loan_type')) is-invalid @endif">
                    <label class="rw-field__label" for="{{ $formIdPrefix }}-loan-type">Loan type</label>
                    <div class="rw-field__control">
                        <select name="loan_type" id="{{ $formIdPrefix }}-loan-type" required>
                            <option value="" disabled @selected(! $defaultLoanType) hidden>Select loan type</option>
                            @foreach (config('riskwisdom.loan_types') as $value => $label)
                                <option value="{{ $value }}" @selected(old('loan_type', $defaultLoanType) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('loan_type')
                        <small class="rw-field__error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="rw-conversion-form__row">
                    <div class="rw-field @if ($errors->has('timeline')) is-invalid @endif">
                        <label class="rw-field__label" for="{{ $formIdPrefix }}-timeline">Timeline</label>
                        <div class="rw-field__control">
                            <select name="timeline" id="{{ $formIdPrefix }}-timeline" required>
                                <option value="" disabled @selected(! old('timeline')) hidden>Select timeline</option>
                                @foreach (config('riskwisdom.timelines') as $value => $label)
                                    <option value="{{ $value }}" @selected(old('timeline') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('timeline')
                            <small class="rw-field__error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="rw-field @if ($errors->has('state')) is-invalid @endif">
                        <label class="rw-field__label" for="{{ $formIdPrefix }}-state">State</label>
                        <div class="rw-field__control">
                            <select name="state" id="{{ $formIdPrefix }}-state" required>
                                <option value="" disabled @selected(! old('state')) hidden>Select state</option>
                                @foreach (config('riskwisdom.states') as $value => $label)
                                    <option value="{{ $value }}" @selected(old('state') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('state')
                            <small class="rw-field__error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="rw-field @if ($errors->has('enquiry')) is-invalid @endif">
                    <label class="rw-field__label" for="{{ $formIdPrefix }}-enquiry">Tell us what you need</label>
                    <div class="rw-field__control">
                        <textarea
                            name="enquiry"
                            id="{{ $formIdPrefix }}-enquiry"
                            rows="3"
                            placeholder="Example: I want to refinance a $480k loan and lower my repayments."
                            required
                        >{{ old('enquiry') }}</textarea>
                    </div>
                    @error('enquiry')
                        <small class="rw-field__error">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <div class="rw-conversion-form__footer">
            <div class="rw-conversion-form__consent">
                @include('partials.marketing-consent')
            </div>

            <button
                type="submit"
                class="rw-button rw-button--solid rw-button--wide rw-conversion-form__submit"
                data-cta="conversion-submit"
                data-loading-text="Sending your enquiry…"
            >
                {{ $landing['form_cta'] }}
            </button>

            <p class="rw-form-trust rw-conversion-form__trust">
                <span class="rw-conversion-form__trust-icon" aria-hidden="true">🔒</span>
                Secure enquiry · {{ config('riskwisdom.rate_review.callback_promise') }}
            </p>
        </div>
    </form>
</div>
