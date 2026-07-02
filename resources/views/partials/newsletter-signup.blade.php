<section class="rw-newsletter" id="newsletter-signup" aria-labelledby="newsletter-signup-title">
    <div>
        <span class="rw-section-label">Newsletter</span>
        <h2 id="newsletter-signup-title">{{ config('riskwisdom.newsletter.title') }}</h2>
        <p>{{ config('riskwisdom.newsletter.description') }}</p>
        @if (session('newsletter_status'))
            <p class="rw-newsletter__status">{{ session('newsletter_status') }}</p>
        @endif
    </div>

    <form action="{{ route('newsletter.signup') }}" method="post" class="rw-newsletter__form" novalidate>
        @csrf

        <div class="rw-newsletter__fields">
            <label class="rw-newsletter__field @error('first_name', 'newsletter') is-invalid @enderror">
                <span>First name</span>
                <input
                    type="text"
                    name="first_name"
                    value="{{ old('first_name') }}"
                    placeholder="First name"
                    required
                    @error('first_name', 'newsletter') aria-invalid="true" @enderror
                >
            </label>

            <label class="rw-newsletter__field @error('email', 'newsletter') is-invalid @enderror">
                <span>Email</span>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Email address"
                    required
                    @error('email', 'newsletter') aria-invalid="true" @enderror
                >
            </label>

            <button class="rw-button rw-button--solid rw-newsletter__submit" type="submit">Subscribe</button>
        </div>

        @if ($errors->getBag('newsletter')->isNotEmpty())
            <div class="rw-newsletter__alert" role="alert">
                @foreach ($errors->getBag('newsletter')->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </form>
</section>
