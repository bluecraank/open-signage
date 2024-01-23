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
        </nav>
    </div>

    <div id="app">
        <div class="container">
            <div class="box main-box">

                @if ($errors->any())
                    <div class="notification is-warning">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (old('ip') != '')
                    <div class="notification is-info">
                        <span class="has-text-weight-bold">Monitor IP: {{ old('ip') }}</span>
                    </div>
                @endif

                <form action="{{ route('devices.register') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label class="label">{{ __('Enter identification key') }}:</label>
                        <input class="input" type="text" name="secret" placeholder="Secret Key">
                    </div>

                    <div class="field">
                        <button class="button is-primary is-fullwidth"
                            type="submit">{{ __('Register device') }}</button>
                    </div>
                </form>
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
