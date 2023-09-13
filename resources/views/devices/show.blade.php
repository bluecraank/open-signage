@extends('layouts.app')

@section('content')
    <div class="title">{{ __('Device') }} - {{ $device->name }}</div>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Edit device') }}
            </p>

        </header>

        <div class="card-content">
            @can('read devices')
                <div class="columns">
                    <div class="column is-4">
                        @php
                            $slides = $device->presentation?->slides;
                            $currentSlide = $slides?->toArray()[$device->current_slide ?? 0];
                            $preview = $currentSlide['publicpreviewpath'] ?? '/data/img/placeholder.png';
                        @endphp
                        <img width="500" class="monitor-border" src="{{ $preview }}" alt="">
                    </div>

                    @cannot('update devices')
                        <div class="column">
                            <p><b>Name:</b> {{ $device->name }}</p>

                            <p><b>{{ __('Description') }}:</b> {{ $device->description }}</p>

                            <p><b>{{ __('Assigned template') }}:</b>
                                {{ $presentation[$device->presentation_id]['name'] ?? __('No template assigned') }}</p>
                        </div>
                    @endcannot

                    @can('update devices')
                        <div class="column">
                            <form action="{{ route('devices.update', $device->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="field">
                                    <label for="" class="label">Name</label>
                                    <input class="input" type="text" name="name" value="{{ $device->name }}" />
                                </div>
                                <div class="field">
                                    <label for="" class="label">{{ __('Description') }}</label>
                                    <input class="input" type="text" name="description" value="{{ $device->description }}" />
                                </div>

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

                                <label for="" class="label">&nbsp;</label>
                                <button type="submit" class="button is-primary">{{ __('Save') }}</button>
                                <button class="button is-info" type="submit" name="reload"
                                    value="force_reload">{{ __('Force page reload') }}</a>
                            </form>
                        </div>
                    @endcan
                </div>
            @endcan
            <hr>

            <div class="pt-5">
                <div class="columns">
                    <div class="column">
                        <p><b>{{ __('IP Address') }}:</b> <br> {{ $device->ip_address }}</p>
                    </div>

                    <div class="column">
                        <p><b>{{ __('Last connection') }}:</b> <br>
                            {{ Carbon::parse($device->last_seen)->diffForHumans() ?? 'N/A' }}</p>
                    </div>

                    <div class="column">
                        <p><b>{{ __('Last monitor reload') }}:</b> <br>
                            {{ Carbon::parse($device->startup_timestamp)->diffForHumans() ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <p><b>{{ __('Secret') }}:</b> <br> {{ $device->secret }}</p>
                    </div>

                    <div class="column">
                        <p><b>{{ __('Monitor URL') }}:</b> <br> <a target="_blank"
                                href="{{ url(route('devices.monitor', $device->secret)) }}">{{ url(route('devices.monitor', $device->secret)) }}</a>
                        </p>
                    </div>

                    <div class="column">

                    </div>
                </div>

                <div class="pt-5">
                    @if ($device->force_reload)
                        <div class="columns">
                            <div class="column">
                                <div class="notification is-warning">
                                    <b>{{ __('Force page reload') }}</b>
                                    <p>{{ __('This device will reload the page on next connection') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="columns">
                        <div class="column">
                            <div class="notification @if (!$device->registered) is-warning @else is-primary @endif">
                                Status: {{ $device->registered ? __('Successfully registered') : __('Not registered') }}
                                @if (!$device->registered)
                                    <div>
                                        <p><b>Open <a
                                                    href="{{ url(route('devices.register')) }}">{{ url(route('devices.register')) }}</a>
                                                on device and enter following key:</b></p>
                                        <b><code>{{ $device->secret }}</code></b>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
