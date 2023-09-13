<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MIS Manager</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <nav class="navbar is-spaced is-dark" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item" href="/">
                    <b>{{ config('app.name') }}</b>
                </a>

                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false"
                    data-target="navbarBasicExample">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            @auth
                <div id="navbarBasicExample" class="navbar-menu">
                    <div class="navbar-start">
                        <a href="{{ route('devices.index') }}" class="navbar-item">
                            {{ __('Devices') }}
                        </a>

                        <a href="{{ route('presentations.index') }}" class="navbar-item">
                            {{ __('Templates') }}
                        </a>

                    </div>

                    <div class="navbar-end">
                        <div class="navbar-item">
                            {{ strtolower(Auth::user()->email) }}
                            <form class="pl-3" id="logout-form" action="/logout" method="POST">
                                @csrf
                                <a class="has-text-grey-light" onclick="document.getElementById('logout-form').submit()">{{ __('Logout') }}</a>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth
        </nav>
    </div>

    <div id="app">
        <div class="container">
            <div class="box main-box">
                @if ($errors)
                    @foreach ($errors->all() as $error)
                        <div class="notification is-danger">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif

                @if (session()->has('success'))
                    <div class="notification is-response is-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                @yield('content')

                <footer>
                    Contribute on <a href="https://github.com">GitHub</a> | Build with <a
                        href="https://laravel.com">Laravel</a> and <i class="mdi mdi-heart"></i>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>
