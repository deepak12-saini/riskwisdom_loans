@extends('layouts.page')

@section('title', $post['title'].' | Riskwisdom Loans')
@section('meta_description', $post['excerpt'])
@section('canonical', route('guides.show', $post['slug']))

@section('page_content')
    <article class="rw-article">
        <a class="rw-article__back" href="{{ route('guides.index') }}">&larr; All guides</a>
        <time datetime="{{ $post['date'] }}">{{ \Illuminate\Support\Carbon::parse($post['date'])->format('j F Y') }}</time>
        <h1>{{ $post['title'] }}</h1>
        <div class="rw-article__content">
            {!! $content !!}
        </div>
    </article>

    <div class="rw-page-cta-band">
        <h2>Want personalised advice?</h2>
        <p>Book a free consultation and get guidance tailored to your borrowing goals.</p>
        <a class="rw-button rw-button--solid" href="{{ contact_url() }}" data-cta="guide-cta">Get free loan review</a>
    </div>
@endsection
