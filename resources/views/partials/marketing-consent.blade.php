<label class="rw-calculator__checkbox rw-form-consent @if ($errors->has('marketing_consent')) is-invalid @endif">
    <input
        type="checkbox"
        name="marketing_consent"
        value="1"
        @checked(old('marketing_consent'))
    >
    <span>{{ config('mailchimp.marketing_consent_label') }}</span>
    @error('marketing_consent')
        <small>{{ $message }}</small>
    @enderror
</label>
