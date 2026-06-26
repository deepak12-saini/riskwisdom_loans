@extends('layouts.site')

@section('content')
    <main class="rw-page">
        <section class="rw-section rw-section--page">
            <div class="container rw-page-card">
                @yield('page_content')
            </div>
        </section>
    </main>
@endsection

@section('header_class')
    rw-header--static
@endsection
