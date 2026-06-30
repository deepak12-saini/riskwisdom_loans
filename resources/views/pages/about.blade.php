@extends('layouts.page')

@section('title', 'About Riskwisdom Loans | Riskwisdom Loans')
@section('meta_description', 'Learn about Riskwisdom Loans, our lending philosophy, and the practical guidance we provide to Australian borrowers.')
@section('canonical', route('pages.about'))

@section('page_content')
    <span class="rw-section-label">{{ config('riskwisdom.about.eyebrow') }}</span>
    <h1>{{ config('riskwisdom.about.heading') }}</h1>

    @foreach (config('riskwisdom.about.story', []) as $paragraph)
        <p class="rw-page-lead" @if (! $loop->first) style="margin-top: 1rem;" @endif>{{ $paragraph }}</p>
    @endforeach

    <div class="rw-page-related">
        <h2>How we work</h2>
        <ul class="rw-page-bullets">
            @foreach (config('riskwisdom.about.principles', []) as $principle)
                <li>{{ $principle }}</li>
            @endforeach
        </ul>
    </div>

    <div class="rw-page-cta-band">
        <h2>Ready to talk through your loan options?</h2>
        <p>Book a call or send an enquiry and we will help you understand the next best step.</p>
        <div class="rw-page-actions">
            <a class="rw-button rw-button--solid" href="{{ route('book') }}">Book a call</a>
            <a class="rw-button rw-button--outline" href="{{ contact_url() }}">Send an enquiry</a>
        </div>
    </div>
@endsection
