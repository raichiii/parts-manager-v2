<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
    <link rel="icon" href="/storage/images/icon.png" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    @yield('css')
</head>

<body>
    <div class="container-fluid" style="color: #875D44;">
        @include('layouts.header')
        @auth
        <main class="py-3">
            <div id="sidebar">
                @include('layouts.sidebar_sp')
            </div>
            <div id="main" class="w-100">
                @include('layouts.components.flash_message')
                @yield('breadcrumbs')
                @yield('content')
            </div>
        </main>
        @else
        <main class="py-4">
            @yield('content')
        </main>
        @endauth
        @include('layouts.footer')
    </div>
    <div class="fixed-alert-flg alert"></div>
    @yield('js')
</body>

</html>
