<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Free Loan Enquiry | Riskwisdom Loans')</title>
        <meta name="description" content="@yield('meta_description', 'Tell us what you need and a Riskwisdom Loans broker will call you back.')">
        <meta name="robots" content="@yield('meta_robots', 'noindex, follow')">
        <link rel="canonical" href="@yield('canonical', url()->current())">
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
        <meta property="og:type" content="website">
        <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title')))">
        <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description')))">
        <meta property="og:url" content="@yield('canonical', url()->current())">
        <meta property="og:image" content="{{ asset('images/risk-wisdom-loans-logo.png') }}">
        @stack('head')
        @include('partials.tracking-head')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="site-body rw-theme rw-body--conversion">
        @include('partials.tracking-body')
        @include('partials.conversion-header')
        @yield('content')
        @include('partials.conversion-footer')
        @include('partials.sticky-cta', ['stickyVariant' => 'call-only'])
        @stack('scripts')
    </body>
</html>
