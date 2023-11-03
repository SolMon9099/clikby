<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100 scroll-behavior-smooth {{ (config('settings.dark_mode') == 1 ? 'dark' : '') }}" dir="{{ (__('lang_dir') == 'rtl' ? 'rtl' : 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('site_title')</title>

    @yield('head_content')

    <link href="{{ url('/') }}/uploads/brand/{{ config('settings.favicon') ?? 'favicon.png' }}" rel="icon">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('assets/js/jquery.min.js') }}" defer></script> --}}
    {{-- <script src="{{ asset('assets/js/popper.min.js') }}" defer></script> --}}
    {{-- <script src="{{ asset('assets/js/bootstrap.min.js') }}" defer></script> --}}
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/wow.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/odometer.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/jquery.appear.js') }}" defer></script>
    <script src="{{ asset('assets/js/form-validator.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/contact-form-script.js') }}" defer></script>
    <script src="{{ asset('assets/js/jquery.ajaxchimp.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/custom.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}?{{ date('Ymdhis') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.min.css') }}?{{ date('Ymdhis') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/magnific-popup.css') }}?{{ date('Ymdhis') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/owl.carousel.min.css') }}?{{ date('Ymdhis') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/owl.theme.default.min.css') }}?{{ date('Ymdhis') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/line-awesome.min.css') }}?{{ date('Ymdhis') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/odometer.css') }}?{{ date('Ymdhis') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}?{{ date('Ymdhis') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/responsive.css') }}?{{ date('Ymdhis') }}" rel="stylesheet">
    <link rel="icon" type="images/png" href="{{ asset('assets/images/favicon.png') }}?{{ date('Ymdhis') }}">
    <link href="{{ asset('css/app'. (__('lang_dir') == 'rtl' ? '.rtl' : '') . (config('settings.dark_mode') == 1 ? '.dark' : '').'.css') }}" rel="stylesheet" data-theme-light="{{ asset('css/app'. (__('lang_dir') == 'rtl' ? '.rtl' : '') . '.css') }}" data-theme-dark="{{ asset('css/app'. (__('lang_dir') == 'rtl' ? '.rtl' : '') . '.dark.css') }}" data-theme-target="href">

    @if(isset(parse_url(config('app.url'))['host']) && parse_url(config('app.url'))['host'] == request()->getHost())
        {!! config('settings.custom_js') !!}
    @endif

    @if(config('settings.custom_css'))
        <style>
          {!! config('settings.custom_css') !!}
        </style>
    @endif
</head>
@yield('body')
</html>
