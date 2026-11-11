@extends('layouts.app')

@section('title', __('Login'))

@section('content')
    <div class="auth-page">
        <div class="card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="brand-mark mb-3" style="width:3rem;height:3rem;font-size:1.4rem"><i class="bi bi-display"></i></span>
                    <h1 class="h4 mb-1">{{ config('app.name') }}</h1>
                    <p class="text-muted mb-0">{{ __('Sign in with your company account') }}</p>
                </div>

                @error('username')
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-octagon"></i>
                        <div>{{ $message }}</div>
                    </div>
                @enderror

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">{{ __('Username') }}</label>
                        <input value="{{ old('username') }}" type="text" name="username" autocomplete="username" autofocus
                            class="@error('username') is-invalid @enderror form-control form-control-lg" id="username" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input type="password" name="password" autocomplete="current-password"
                            class="@error('username') is-invalid @enderror form-control form-control-lg" id="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">{{ __('Login') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
