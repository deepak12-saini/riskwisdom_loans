<div class="rw-chat-widget @if (session('chat_open')) is-open @endif" id="after-hours-chat">
    <button class="rw-chat-widget__toggle" type="button" data-chat-toggle aria-expanded="{{ session('chat_open') ? 'true' : 'false' }}" aria-controls="after-hours-chat-panel">
        {{ config('riskwisdom.chat_widget.button_label') }}
    </button>

    <div class="rw-chat-widget__overlay" data-chat-overlay @if (! session('chat_open')) hidden @endif></div>

    <div class="rw-chat-widget__panel" id="after-hours-chat-panel" @if (! session('chat_open')) hidden @endif>
        <div class="rw-chat-widget__header">
            <strong>{{ config('riskwisdom.chat_widget.title') }}</strong>
            <p>{{ config('riskwisdom.chat_widget.message') }}</p>
            <button type="button" class="rw-chat-widget__close" data-chat-close aria-label="Close after-hours chat">×</button>
        </div>

        <form action="{{ route('chat.capture') }}" method="post" class="rw-chat-widget__form">
            @csrf
            <input type="text" name="_gotcha" value="" tabindex="-1" autocomplete="off" class="rw-form-honeypot" aria-hidden="true">

            <div class="rw-chat-widget__grid">
                <label>
                    <span>First name</span>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required>
                    @error('first_name', 'chat')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    <span>Last name</span>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required>
                    @error('last_name', 'chat')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                @include('partials.phone-field', [
                    'variant' => 'label',
                    'fullWidth' => true,
                    'errorBag' => 'chat',
                    'placeholder' => 'Phone number',
                ])
                <label class="rw-chat-widget__full">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email', 'chat')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                <label class="rw-chat-widget__full">
                    <span>Loan type</span>
                    <select name="loan_type">
                        <option value="">Select loan type (optional)</option>
                        @foreach (config('riskwisdom.loan_types') as $value => $label)
                            <option value="{{ $value }}" @selected(old('loan_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="rw-chat-widget__full">
                    <span>How can we help?</span>
                    <textarea name="enquiry" rows="4" placeholder="Tell us what you need help with" required>{{ old('enquiry') }}</textarea>
                    @error('enquiry', 'chat')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
            </div>

            @include('partials.marketing-consent')

            <button class="rw-button rw-button--solid rw-button--wide" type="submit">Send message</button>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.querySelectorAll('[data-chat-toggle]').forEach((button) => {
            const widget = button.closest('.rw-chat-widget');
            const panel = widget?.querySelector('#after-hours-chat-panel');
            const overlay = widget?.querySelector('[data-chat-overlay]');
            const closeButton = widget?.querySelector('[data-chat-close]');

            if (! panel || ! overlay) {
                return;
            }

            const openChat = () => {
                panel.removeAttribute('hidden');
                overlay.removeAttribute('hidden');
                button.setAttribute('aria-expanded', 'true');
                widget?.classList.add('is-open');
            };

            const closeChat = () => {
                panel.setAttribute('hidden', 'hidden');
                overlay.setAttribute('hidden', 'hidden');
                button.setAttribute('aria-expanded', 'false');
                widget?.classList.remove('is-open');
            };

            button.addEventListener('click', () => {
                const isHidden = panel.hasAttribute('hidden');

                if (isHidden) {
                    openChat();
                } else {
                    closeChat();
                }
            });

            overlay.addEventListener('click', closeChat);
            closeButton?.addEventListener('click', closeChat);
        });
    </script>
@endpush
