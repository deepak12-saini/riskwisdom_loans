<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Risk Wisdom Loans | Riskwisdom Loans')</title>
        <meta name="description" content="@yield('meta_description', 'Risk Wisdom Loans helps Australian borrowers with home loans, refinancing, investment lending, commercial finance, and asset finance.')">
        <meta name="robots" content="@yield('meta_robots', 'index, follow')">
        <link rel="canonical" href="@yield('canonical', url('/'))">
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
        <meta property="og:type" content="website">
        <meta property="og:title" content="@yield('og_title', 'Risk Wisdom Loans | Riskwisdom Loans')">
        <meta property="og:description" content="@yield('og_description', 'Clear lending guidance for home loans, refinancing, investment, commercial, and asset finance across Australia.')">
        <meta property="og:url" content="@yield('canonical', url('/'))">
        <meta property="og:image" content="{{ asset('images/risk-wisdom-loans-logo.png') }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('og_title', 'Risk Wisdom Loans | Riskwisdom Loans')">
        <meta name="twitter:description" content="@yield('og_description', 'Clear lending guidance for home loans, refinancing, investment, commercial, and asset finance across Australia.')">
        @stack('head')
        @include('partials.tracking-head')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="site-body rw-theme @yield('body_class')">
        @include('partials.tracking-body')
        @php
            $headerClass = trim($__env->yieldContent('header_class'));
            if ($headerClass === '') {
                $headerClass = 'rw-header--static';
            }
        @endphp
        @include('partials.header', ['headerClass' => $headerClass])
        @yield('content')
        @include('partials.footer')
        @include('partials.after-hours-chat')
        @include('partials.sticky-cta', [
            'stickyVariant' => trim($__env->yieldContent('sticky_variant')) ?: 'default',
        ])
        @stack('scripts')
    </body>
</html>
