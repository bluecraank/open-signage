<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MIS Manager</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="container">
    @include('layouts.menu')

    <div id="app">
        @if ($errors)
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger fade-out">
                    {{ $error }}
                </div>
            @endforeach
        @endif

        @if (session()->has('success'))
            <div class="alert alert-success fade-out">
                {{ session()->get('success') }}
            </div>
        @endif

        @yield('content')

        <footer>
            Build with <a href="https://laravel.com">Laravel</a>, <a href="https://bulma.io">Bulma</a> and <i
                class="mdi mdi-heart"></i> | v{{ config('app.version') }}
        </footer>
    </div>
</body>

</html>
