<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="icon" type="image/png" href="/data/img/favicon.png">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="container">
    <script>
        // Instantly switch between light and dark mode to prevent flash of light mode
        const body = document.querySelector('body');
        if (localStorage.getItem('theme') === 'dark') {
            body.dataset.bsTheme = 'dark';
        } else {
            body.dataset.bsTheme = '';
        }
    </script>
    @auth
        @include('layouts.menu')
    @endauth

    <div id="app">
        @auth
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
        @endauth

        @yield('content')

        @include('layouts.footer')
    </div>
</body>

</html>
