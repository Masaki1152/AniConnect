<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="/css/lightbox.min.css">
    <link rel="stylesheet" href="/css/preview.css">
    <link rel="stylesheet" href="/css/category_form.css">
    <link rel="stylesheet" href="/css/cropper.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Scripts -->
    <script>
        window.Lang = {!! json_encode([
            'messages' => trans('messages'),
            'common' => trans('common'),
            'validation' => trans('validation'),
        ]) !!};

        window.categoryColors = {!! json_encode($categoryColors) !!};

        window.App = window.App || {};
        window.App.userLoggedIn = @auth true
        @else
            false
        @endauth ;
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased min-h-screen flex flex-col">
    @include('layouts.navigation')

    <!-- Page Content -->
    <main class="flex-grow bg-baseColor pt-16">
        {{ $slot }}
    </main>
    <x-atom.footer />
    <x-molecules.dialog.login-dialog />
    <!-- Lightbox JS -->
    <script src="/js/lightbox-plus-jquery.min.js"></script>
    <script src="/js/cropper.js"></script>
    <script src="/js/login_dialog.js"></script>
    @vite(['resources/js/app.js'])
</body>

</html>
