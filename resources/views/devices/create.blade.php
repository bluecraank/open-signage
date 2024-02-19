@extends('layouts.app')

@section('content')
    <h3 class="mb-3">{{ __('Create device') }}</h3>

    <div class="card">
        <h5 class="card-header">{{ __('Overview') }}</h5>
        <div class="card-body">
            <p>
            <div class="alert alert-info">
                <p>{{ __('To create a new device, follow these steps:') }}</p>
            </div>

            <ol class="m-2">
                <li>Open <b>{{ url('/discover') }}</b> on monitor</li>
                <li>If a monitor with request ip already exists, auto routing to presentation mode will be triggered</li>
                <li>Else, open up the new monitor in admin panel and accept registration</li>
            </ol>

            <div class="alert alert-info">
                <p>{{ __('This page has been changed, its no longer possible to create a monitor manually') }}</p>
            </div>
            </p>
        </div>
    </div>
@endsection
