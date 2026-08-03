@php
    $inputId = $id ?? 'phone';
    $countryId = ($id ?? 'phone').'-country';
    $variant = $variant ?? 'field';
    $fieldLabel = $label ?? 'Phone';
    $fieldPlaceholder = $placeholder ?? 'Phone number';
    $isRequired = $required ?? true;
    $errorBag = $errorBag ?? null;
    $invalid = $errorBag
        ? ($errors->{$errorBag}->has('phone') || $errors->{$errorBag}->has('phone_country_code'))
        : ($errors->has('phone') || $errors->has('phone_country_code'));
    $split = split_lead_phone($value ?? null);
    $selectedCountry = old('phone_country_code', $split['phone_country_code']);
    $nationalNumber = old('phone', $split['phone']);
@endphp

@if ($variant === 'label')
    <label @class(['is-invalid' => $invalid, 'rw-form-full' => $fullWidth ?? false])>
        <span>{{ $fieldLabel }} @if ($isRequired)<em class="rw-required">*</em>@endif</span>
        <div class="rw-phone-field">
            <select
                name="phone_country_code"
                id="{{ $countryId }}"
                class="rw-phone-field__country"
                aria-label="Country code"
                @if ($isRequired) required @endif
            >
                @foreach (phone_country_codes() as $code => $countryLabel)
                    <option value="{{ $code }}" @selected($selectedCountry === $code)>{{ $countryLabel }}</option>
                @endforeach
            </select>
            <input
                type="tel"
                name="phone"
                id="{{ $inputId }}"
                class="rw-phone-field__input"
                value="{{ $nationalNumber }}"
                placeholder="{{ $fieldPlaceholder }}"
                inputmode="tel"
                autocomplete="tel-national"
                @if ($isRequired) required @endif
            >
        </div>
        @if ($errorBag)
            @error('phone', $errorBag)
                <small>{{ $message }}</small>
            @enderror
            @error('phone_country_code', $errorBag)
                <small>{{ $message }}</small>
            @enderror
        @else
            @error('phone')
                <small>{{ $message }}</small>
            @enderror
            @error('phone_country_code')
                <small>{{ $message }}</small>
            @enderror
        @endif
    </label>
@else
    <div @class(['rw-field', 'rw-field--phone', 'is-invalid' => $invalid, 'rw-form-full' => $fullWidth ?? false])>
        <label class="rw-field__label" for="{{ $inputId }}">{{ $fieldLabel }}</label>
        <div class="rw-phone-field">
            <select
                name="phone_country_code"
                id="{{ $countryId }}"
                class="rw-phone-field__country"
                aria-label="Country code"
                @if ($isRequired) required @endif
            >
                @foreach (phone_country_codes() as $code => $countryLabel)
                    <option value="{{ $code }}" @selected($selectedCountry === $code)>{{ $countryLabel }}</option>
                @endforeach
            </select>
            <div class="rw-field__control rw-phone-field__number">
                <input
                    type="tel"
                    name="phone"
                    id="{{ $inputId }}"
                    value="{{ $nationalNumber }}"
                    placeholder="{{ $fieldPlaceholder }}"
                    inputmode="tel"
                    autocomplete="tel-national"
                    @if ($isRequired) required @endif
                >
            </div>
        </div>
        @error('phone')
            <small class="rw-field__error">{{ $message }}</small>
        @enderror
        @error('phone_country_code')
            <small class="rw-field__error">{{ $message }}</small>
        @enderror
    </div>
@endif
