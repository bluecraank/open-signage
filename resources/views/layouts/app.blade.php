<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') · @endif{{ config('app.name') }}</title>

    <link rel="icon" type="image/png" href="/data/img/favicon.png">

    <script>
        // Set the theme before the first paint to prevent a flash of the wrong theme.
        // Without a saved choice the theme follows the operating system.
        (function() {
            let theme = null;
            try { theme = localStorage.getItem('theme'); } catch (e) {}
            if (theme !== 'dark' && theme !== 'light') {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.dataset.bsTheme = theme;
        })();

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

<body>
    @auth
        @include('layouts.menu')
    @endauth

    <main id="app" class="@auth app-main container-xl @else d-flex flex-column flex-grow-1 @endauth">
        @auth
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger fade-out" role="alert">
                    <i class="bi bi-exclamation-octagon"></i>
                    <div>{{ $error }}</div>
                </div>
            @endforeach

            @if (session()->has('success'))
                <div class="alert alert-success fade-out" role="status">
                    <i class="bi bi-check-circle"></i>
                    <div>{{ session()->get('success') }}</div>
                </div>
            @endif
        @endauth

        @yield('content')
    </main>

    @include('layouts.footer')
</body>

</html>
