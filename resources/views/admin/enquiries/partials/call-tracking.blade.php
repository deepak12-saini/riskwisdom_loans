@php
    $callStatus = old('call_status', $enquiry->call_status ?? 'new');
    $callbackValue = old('callback_at', $enquiry->callback_at?->format('Y-m-d\TH:i'));
    $statuses = config('riskwisdom.call_statuses', []);
@endphp

<section class="rw-lead-panel rw-lead-panel--call-tracking" id="call-tracking">
    <div class="rw-lead-panel__intro">
        <div>
            <h2>Call tracking</h2>
            <p>Log what happened on the call so the team knows the next step.</p>
        </div>
        <span class="rw-admin-pill rw-admin-pill--call-{{ $callStatus }}" data-call-status-pill>{{ $statuses[$callStatus] ?? ucfirst($callStatus) }}</span>
    </div>

    <form method="post" action="{{ route('admin.enquiries.call-tracking.update', $enquiry) }}" class="rw-call-tracking-form">
        @csrf
        @method('PATCH')

        <fieldset class="rw-call-tracking-form__section">
            <legend>Call status</legend>
            <div class="rw-call-status-picker" role="radiogroup" aria-label="Call status">
                @foreach ($statuses as $value => $label)
                    <label class="rw-call-status-option rw-call-status-option--{{ $value }} @if ($callStatus === $value) is-active @endif">
                        <input
                            type="radio"
                            name="call_status"
                            value="{{ $value }}"
                            @checked($callStatus === $value)
                            data-call-status-input
                            required
                        >
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('call_status')<small class="rw-field__error">{{ $message }}</small>@enderror
        </fieldset>

        <div class="rw-call-tracking-form__callback @if ($callStatus !== 'callback') is-hidden @endif" data-callback-field>
            <label class="rw-call-tracking-form__callback-label">
                <span>Callback date &amp; time</span>
                <input type="datetime-local" name="callback_at" value="{{ $callbackValue }}">
            </label>
            @error('callback_at')<small class="rw-field__error">{{ $message }}</small>@enderror
        </div>

        <fieldset class="rw-call-tracking-form__section">
            <legend>Call notes</legend>
            <label class="rw-call-notes-field">
                <span class="visually-hidden">Call notes</span>
                <textarea
                    name="call_notes"
                    rows="5"
                    placeholder="What did you discuss? Include timing, interest level, and next step — e.g. Called 2pm, wants refinance review, booked broker call Tuesday 3pm."
                >{{ old('call_notes', $enquiry->call_notes) }}</textarea>
            </label>
            <p class="rw-call-notes-field__hint">Visible to staff only — not sent to the client.</p>
            @error('call_notes')<small class="rw-field__error">{{ $message }}</small>@enderror
        </fieldset>

        <div class="rw-call-tracking-form__footer">
            @if ($enquiry->last_called_at)
                <p class="rw-call-tracking-form__meta">Last call update: {{ $enquiry->last_called_at->format('d M Y g:ia') }}</p>
            @else
                <p class="rw-call-tracking-form__meta">No call logged yet.</p>
            @endif
            <button class="rw-button rw-button--solid" type="submit">Save call update</button>
        </div>
    </form>
</section>

@push('scripts')
    <script>
        (() => {
            const form = document.querySelector('.rw-call-tracking-form');
            if (!form) {
                return;
            }

            const callbackField = form.querySelector('[data-callback-field]');
            const statusPill = document.querySelector('[data-call-status-pill]');
            const statusLabels = @json($statuses);

            const syncStatusUi = (value) => {
                form.querySelectorAll('[data-call-status-input]').forEach((input) => {
                    input.closest('.rw-call-status-option')?.classList.toggle('is-active', input.value === value);
                });

                if (callbackField) {
                    callbackField.classList.toggle('is-hidden', value !== 'callback');
                }

                if (statusPill) {
                    statusPill.textContent = statusLabels[value] || value;
                    statusPill.className = `rw-admin-pill rw-admin-pill--call-${value}`;
                    statusPill.setAttribute('data-call-status-pill', '');
                }
            };

            form.querySelectorAll('[data-call-status-input]').forEach((input) => {
                input.addEventListener('change', () => syncStatusUi(input.value));
            });
        })();
    </script>
@endpush
