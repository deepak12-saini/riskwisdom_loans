@extends('layouts.page')

@section('title', 'Guides & Insights | Riskwisdom Loans')
@section('meta_description', 'Practical home loan guides for Australian borrowers — refinancing, first home buyers, borrowing power, and investment lending.')
@section('canonical', route('guides.index'))

@section('page_content')
    <span class="rw-section-label">Guides & Insights</span>
    <h1>Finance guides for Australian borrowers</h1>
    <p class="rw-page-lead">Practical articles to help property owners and buyers make clearer lending decisions.</p>

    <div class="rw-grid rw-grid--guides">
        @foreach ($posts as $post)
            <article class="rw-card rw-card--guide">
                <time datetime="{{ $post['date'] }}">{{ \Illuminate\Support\Carbon::parse($post['date'])->format('j M Y') }}</time>
                <h2><a href="{{ route('guides.show', $post['slug']) }}">{{ $post['title'] }}</a></h2>
                <p>{{ $post['excerpt'] }}</p>
                <a class="rw-link-arrow" href="{{ route('guides.show', $post['slug']) }}">Read article</a>
            </article>
        @endforeach
    </div>
@endsection
