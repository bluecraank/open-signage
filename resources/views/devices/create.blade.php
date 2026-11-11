@extends('layouts.app')

@section('title', __('Create device'))

@section('content')
    <x-page-header :title="__('Create device')" :back="route('devices.index')" :backLabel="__('Devices')" />

    <div class="card">
        <div class="card-body p-4">
            <p class="text-muted">{{ __('To create a new device, follow these steps:') }}</p>

            <ol class="list-group list-group-numbered list-group-flush mb-0">
                <li class="list-group-item bg-transparent">
                    {{ __('Open this address in the browser of the monitor:') }}
                    <code class="ms-1">{{ url('/discover') }}</code>
                </li>
                <li class="list-group-item bg-transparent">
                    {{ __('The monitor appears in the device list and waits for approval. A monitor whose IP address is already known starts its presentation right away.') }}
                </li>
                <li class="list-group-item bg-transparent">
                    {{ __('Open the new monitor in the device list, accept it and assign a template.') }}
                </li>
            </ol>
        </div>
    </div>
@endsection
