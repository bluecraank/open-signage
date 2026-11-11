<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="icon" type="image/png" href="/data/img/favicon.png">

    <script>
        // After a longer inactivity (standby, expired session, container restart) the csrf token
        // is no longer valid and Livewire polling gets a 419. Instead of Livewire's
        // "This page has expired" dialog, reload the page (redirects to login if needed).
        document.addEventListener('livewire:init', () => {
            Livewire.hook('request', ({ fail }) => {
                fail(({ status, preventDefault }) => {
                    if (status !== 419) return;

                    // Prevent a reload loop if the session cookie can't be stored at all
                    let lastReload = 0;
                    try { lastReload = Number(sessionStorage.getItem('livewire-419-reload')) || 0; } catch (e) {}
                    if (Date.now() - lastReload < 30000) return;

                    try { sessionStorage.setItem('livewire-419-reload', Date.now()); } catch (e) {}
                    preventDefault();
                    window.location.reload();
                });
            });
        });
    </script>

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
