@if (! empty($faqs))
    @if ($faqFullWidth ?? true)
        <section class="rw-landing-faq" aria-labelledby="landing-faq-title">
            <div class="container">
    @endif

    <div class="rw-faq rw-faq--landing @if (! ($faqFullWidth ?? true)) rw-faq--embedded @endif" @if ($faqFullWidth ?? true) aria-labelledby="landing-faq-title" @endif>
        <h2 id="landing-faq-title">{{ $faqHeading ?? 'Frequently asked questions' }}</h2>
        <div class="rw-faq__list">
            @foreach ($faqs as $faq)
                <details class="rw-faq__item" @if ($loop->first) open @endif>
                    <summary>
                        <span class="rw-faq__question">{{ $faq['question'] }}</span>
                        <span class="rw-faq__toggle" aria-hidden="true"></span>
                    </summary>
                    <div class="rw-faq__answer">
                        <p>{{ $faq['answer'] }}</p>
                    </div>
                </details>
            @endforeach
        </div>
    </div>

    @if ($faqFullWidth ?? true)
            </div>
        </section>
    @endif
@endif
