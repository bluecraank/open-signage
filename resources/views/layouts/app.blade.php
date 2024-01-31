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
    <div class="columns is-centered">
        <div class="column is-10">

    <div class="container is-fluid">
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
                                    @can('read logs')
                                        <a href="{{ route('logs.index') }}" class="navbar-item">
                                            {{ __('Logs') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        @endcan
                    </div>

                    <div class="navbar-end">
                        <div class="navbar-item">
                            @livewire('poll-presentation-process')
                        </div>
                        {{-- <div class="navbar-item">
                            {{ __('Logged in as') }} {{ Auth::user()->name }}
                            @if (!config('app.sso_enabled') || !isset($_SERVER[config('app.sso_http_header_user_key')]))
                                <form class="pl-3" id="logout-form" action="/logout" method="POST">
                                    @csrf
                                    <a class="has-text-grey-light"
                                        onclick="document.getElementById('logout-form').submit()">{{ __('Logout') }}</a>
                                </form>
                            @endif
                        </div> --}}
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                <figure class="image is-32x32 mt-2 mr-2 ">
                                    <img class="" src="https://ui-avatars.com/api/?background=random&rounded=true&name={{ Auth::user()->name }}"
                                        alt="">
                                </figure>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="navbar-dropdown is-right">
                                <form id="logout-form" action="/logout" method="POST">
                                    @csrf
                                    <span class="navbar-item is-size-7">
                                        {{ Auth::user()->roles->first()->name }}
                                    </span>
                                    <a class="navbar-item" onclick="document.getElementById('logout-form').submit()">
                                        {{ __('Logout') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
        </nav>
    </div>

    <div id="app">
        <div class="container is-fluid">
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
                    Build with <a href="https://laravel.com">Laravel</a>, <a href="https://bulma.io">Bulma</a> and <i
                        class="mdi mdi-heart"></i> | v{{ config('app.version') }}
                </footer>
            </div>
        </div>
    </div>
    </div>
    </div>
</body>

</html>
