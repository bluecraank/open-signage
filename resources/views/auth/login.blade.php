    @extends('layouts.app')

    @section('content')
        <div class="container">
            <div class="columns pt-5">
                <div class="column is-6 is-offset-3 pt-5">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                                {{ __('Login') }}
                            </p>
                        </header>

                        <div class="card-content">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="field">
                                    <label for="email" class="label">{{ __('Username') }}</label>

                                    <input id="username" type="text"
                                        class="input @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') }}" required autocomplete="username" autofocus />
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="password" class="label">{{ __('Password') }}</label>


                                    <input id="password" type="password"
                                        class="input @error('password') is-invalid @enderror" name="password" required
                                        autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                                <div class="field">
                                    <button type="submit" class="button is-primary">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
