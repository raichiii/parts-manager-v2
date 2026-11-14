<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <div class="container-fluid">
        @include('layouts.header')
        @auth
        <main class="py-3">
            <div id="sidebar">
                <!-- パソコン／スマホ別でハンバーガーメニューのスタイルを変更
                    すべてのページでスマホ用画面ができれば消しても良い -->
                @if($isMobile)
                @include('layouts.sidebar_sp')
                @else
                @include('layouts.sidebar')
                @endif
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
    <!--
    .fixed-alert-flgの使い方はboard/parts/kensaku.js等を参照
    classにfixed-alertとalert-warningを追加しdiv内の文字を追加すればアラートが出る
    5秒後にfixed-alertを消せば跡形もなくなる
    -->
    <div class="fixed-alert-flg alert"></div>
    @yield('js')
</body>

</html>
