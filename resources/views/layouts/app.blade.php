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
    <div id="app">

        <section class="main-content columns is-fullheight">
            @auth
            <aside class="column is-2 is-narrow-mobile is-fullheight is-hidden-mobile has-background-light">
                <div class="logo has-text-centered p-4 is-size-4">
                    <b>{{ config('app.name') }}</b>
                </div>
                <ul class="custom-menu">
                    <li>
                        <a href="/devices" class="">
                            <span class="icon"><i class="fa fa-home"></i></span> {{ __('Devices') }}
                        </a>
                    </li>
                    <li>
                        <a href="/presentations" class="is-active">
                            <span class="icon"><i class="fa fa-table"></i></span> {{ __('Templates') }}
                        </a>
                    </li>
                </ul>

                <ul class="pt-5">
                    <li class="has-text-centered">{{ Auth::user()->getName() }}</li>
                    <form action="/logout" method="POST">
                        @csrf
                        <li class="has-text-centered"><button class="button is-danger is-small" type="submit">{{ __('Logout') }}</button></li>
                    </form>
                </ul>
            </aside>
            @endauth

            <div class="container column is-10 some-space">
                    @yield('content')
            </div>

        </section>
    </div>
</body>

</html>
