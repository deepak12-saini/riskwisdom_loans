@extends('layouts.site')

@section('content')
    <main class="rw-page rw-calculator-page">
        <section class="rw-section rw-section--page rw-section--calculator">
            <div class="container rw-calculator-page__container">
                @include('partials.calculator-switcher', ['calculator' => $calculator ?? ''])

                <div class="rw-calculator-page__layout">
                    <aside class="rw-calculator-page__intro">
                        @yield('calculator_intro')
                    </aside>

                    <div class="rw-calculator-page__panel">
                        @yield('calculator_panel')
                    </div>
                </div>

                @hasSection('calculator_footer')
                    @yield('calculator_footer')
                @endif
            </div>
        </section>
    </main>
@endsection

@section('header_class')
    rw-header--static
@endsection
