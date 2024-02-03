    @extends('layouts.app')

    @section('content')
        <div class="container py-5 d-flex justify-content-center" style="height:90vh;">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <h1>
                    {{ config('app.name') }}
                </h1>
                <div class="card mt-3" style="width:35rem">
                    <h5 class="card-header">
                        {{ __('Login') }}
                    </h5>
                    <div class="card-body">
                        @error('username')
                            <div class="alert alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">{{ __('Username') }}</label>
                                <input value="{{ old('username') }}" type="text" name="username"
                                    class="@error('username') is-invalid @enderror form-control" id="username">

                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" name="password"
                                    class="@error('username') is-invalid @enderror form-control" id="password">
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
