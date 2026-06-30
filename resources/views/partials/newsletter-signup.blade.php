<section class="rw-newsletter" id="newsletter-signup" aria-labelledby="newsletter-signup-title">
    <div>
        <span class="rw-section-label">Newsletter</span>
        <h2 id="newsletter-signup-title">{{ config('riskwisdom.newsletter.title') }}</h2>
        <p>{{ config('riskwisdom.newsletter.description') }}</p>
        @if (session('newsletter_status'))
            <p class="rw-newsletter__status">{{ session('newsletter_status') }}</p>
        @endif
    </div>

    <form action="{{ route('newsletter.signup') }}" method="post" class="rw-newsletter__form">
        @csrf
        <label>
            <span>First name</span>
            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required>
            @error('first_name', 'newsletter')
                <small>{{ $message }}</small>
            @enderror
        </label>

        <label>
            <span>Email</span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required>
            @error('email', 'newsletter')
                <small>{{ $message }}</small>
            @enderror
        </label>

        <button class="rw-button rw-button--solid" type="submit">Subscribe</button>
    </form>
</section>
