@extends('layouts.app')

@section('content')
    @if ($errors)
        @foreach ($errors->all() as $error)
            <div class="notification is-danger">
                {{ $error }}
            </div>
        @endforeach
    @endif

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                Monitor - {{ $device->name }}
            </p>

        </header>

        <div class="card-content">
            <form action="{{ route('devices.update', $device->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label for="" class="label">Name</label>
                            <input class="input" type="text" name="name" value="{{ $device->name }}" />
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label for="" class="label">{{ __('Description') }}</label>
                            <input class="input" type="text" name="description" value="{{ $device->description }}" />
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label for="" class="label">{{ __('Assigned template') }}</label>
                            <div class="select">
                                <select name="presentation_id">
                                    <option value="">{{ __('No template assigned') }}</option>
                                    @foreach ($presentations as $presentation)
                                        <option @if ($device->presentation_id == $presentation->id) selected @endif
                                            value="{{ $presentation->id }}"
                                            @if ($presentation->id == $device->presentation_id) selected @endif>{{ $presentation->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="column">
                        <label for="" class="label">&nbsp;</label>
                        <button type="submit" class="button is-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

            <div class="pt-5">
                <div class="columns">
                    <div class="column">
                        <p><b>{{ __('IP Address') }}:</b> {{ $device->ip_address }}</p>
                    </div>

                    <div class="column">
                        <p><b>{{ __('Last connection') }}:</b> {{ Carbon::parse($device->last_seen)->diffForHumans() ?? 'N/A' }}</p>
                    </div>

                    <div class="column">
                        <p><b>{{ __('Last monitor reload') }}:</b> {{ Carbon::parse($device->startup_timestamp)->diffForHumans() ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <div class="columns pt-5">
                    <div class="column">
                        <div class="notification @if (!$device->registered) is-warning @else is-primary @endif">
                            Status: {{ $device->registered ? __('Successfully registered') : __('Not registered') }}
                            @if (!$device->registered)
                                <div>
                                    <p><b>Open <a href="{{ url(route('devices.register')) }}">{{ url(route('devices.register')) }}</a> on device and enter following key:</b></p>
                                    <b><code>{{ $device->secret }}</code></b>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endsection
