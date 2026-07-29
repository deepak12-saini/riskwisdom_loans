@extends('layouts.site')

@section('content')
    <main class="rw-page rw-page--landing">
        @if (! empty($skipLandingHero))
            @isset($aboutBrokerSection)
                @include('pages._about-broker-hero')
            @endisset
        @else
            @include('pages._landing-hero')
        @endif

        @include('pages._landing-why-choose')

        <section class="rw-landing-main">
            <div class="container">
                @yield('page_content')
            </div>
        </section>

        @include('pages._landing-faq')
    </main>
@endsection

@section('header_class')
    rw-header--overlay
@endsection

@section('body_class')
    rw-body--landing
@endsection
