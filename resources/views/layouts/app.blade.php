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
            </div>

            @auth
                <div id="navbar-main" class="navbar-menu">
                    <div class="navbar-start">
                        @can('read devices')
                            <a href="{{ route('devices.index') }}" class="navbar-item">
                                {{ __('Devices') }}
                            </a>
                        @endcan

                        @can('read groups')
                            <a href="{{ route('groups.index') }}" class="navbar-item">
                                {{ __('Groups') }}
                            </a>
                        @endcan

                        @can('read presentations')
                            <a href="{{ route('presentations.index') }}" class="navbar-item">
                                {{ __('Templates') }}
                            </a>
                        @endcan

                        @can('read schedules')
                            <a href="{{ route('schedules.index') }}" class="navbar-item">
                                {{ __('Schedules') }}
                            </a>
                        @endcan

                        @can('manage settings')
                            <div class="navbar-item has-dropdown is-hoverable">
                                <a class="navbar-link">
                                    {{ __('Settings') }}
                                </a>

                                <div class="navbar-dropdown">
                                    @can('read users')
                                        <a href="{{ route('users.index') }}" class="navbar-item">
                                            {{ __('Users') }}
                                        </a>
                                    @endcan
                                    <a href="{{ route('settings.index') }}" class="navbar-item">
                                        {{ __('Monitorsettings') }}
                                    </a>
                                </div>
                            </div>
                        @endcan
                    </div>

                    <div class="navbar-end">
                        <div class="navbar-item">
                            @livewire('poll-presentation-process')
                        </div>
                        <div class="navbar-item">
                            {{ __('Logged in as') }} {{ Auth::user()->name }}
                            @if (!config('app.sso_enabled') || !isset($_SERVER[config('app.sso_http_header_user_key')]))
                                <form class="pl-3" id="logout-form" action="/logout" method="POST">
                                    @csrf
                                    <a class="has-text-grey-light"
                                        onclick="document.getElementById('logout-form').submit()">{{ __('Logout') }}</a>
                                </form>
                            @else
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
                        <div class="notification is-danger slideout">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif

                @if (session()->has('success'))
                    <div class="notification is-response is-success slideout">
                        {{ session()->get('success') }}
                    </div>
                @endif

                @yield('content')

                <footer>
                    Contribute on <a href="https://github.com/bluecraank/open-signage">GitHub</a> | Build with <a
                        href="https://laravel.com">Laravel</a>, <a href="https://bulma.io">Bulma</a> and <i
                        class="mdi mdi-heart"></i>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>
