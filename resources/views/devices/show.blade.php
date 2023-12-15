@extends('layouts.app')

@section('content')
    <div class="title">{{ $device->name }}</div>
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
                            $slides = $device->getPresentation()?->slides;
                            if($slides?->toArray() != null) {
                                $currentSlide = array_key_exists($device->current_slide ?? 0, $slides?->toArray()) ? $slides?->toArray()[$device->current_slide ?? 0] : $slides?->toArray()[0];
                                $preview = $currentSlide['publicpreviewpath'] ?? config('app.placeholder_image');
                            } else {
                                $preview = config('app.placeholder_image');
                            }
                        @endphp
                        <img width="500" class="monitor-border" src="{{ $preview }}" alt="">

                        @can('force reload monitor')
                            <form method="POST" class="pt-1" action="{{ route('devices.reload', ['id' => $device->id]) }}">
                                @csrf
                                @method('PUT')
                                <button class="button is-info" type="submit" name="reload"
                                    value="force_reload">{{ __('Force page reload') }}</button>
                            </form>
                        @endcan

                        @can('delete devices')
                            <form method="POST" class="pt-1" action="{{ route('devices.destroy', ['id' => $device->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="button is-danger" type="submit"
                                    onclick="return confirm('{{ __('Are you sure?') }}')">{{ __('Delete device') }}</button>
                            </form>
                        @endcan
                    </div>

                    @cannot('update devices')
                        <div class="column">
                            <p><b>Name:</b> {{ $device->name }}</p>

                            <p><b>{{ __('Description') }}:</b> {{ $device->description }}</p>

                            @if ($device->presentationFromGroup())
                                <p><b>{{ __('Assigned template') }}:</b>
                                    {{ $device->getPresentation()?->name ?? __('No template assigned') }}
                                    <br> <small><i class="mdi mdi-checkbox-marked-circle-outline"></i>
                                        {{ __('Inherited by group') }}</small>
                                </p>
                            @elseif($device->presentationFromSchedule())
                                <p><b>{{ __('Assigned template') }}:</b>
                                    {{ $device->getPresentation()?->name ?? __('No template assigned') }}
                                    <br> <small><i class="mdi mdi-checkbox-marked-circle-outline"></i>
                                        {{ __('Inherited by schedule') }} - <a
                                            href="{{ route('schedules.show', $device->getPresentationId()) }}">{{ __('Go to schedule') }}</a></small>
                                </p>
                            @else
                                <p><b>{{ __('Assigned template') }}:</b>
                                    {{ $presentation[$device->getPresentationId()]['name'] ?? __('No template assigned') }}</p>
                            @endif
                        </div>
                    @endcannot

                    <div class="column">
                        @can('update devices')
                            <form action="{{ route('devices.update', $device->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="field">
                                    <label class="label">Name<span class="has-text-danger">*</span></label>
                                    <input class="input" type="text" name="name" value="{{ $device->name }}" />
                                </div>
                                <div class="field">
                                    <label class="label">{{ __('Location') }}</label>
                                    <input class="input" type="text" name="description" value="{{ $device->description }}" />
                                </div>

                                @if ($device->presentationFromGroup())
                                    <p><b>{{ __('Assigned template') }}:</b>
                                        {{ $device->getPresentation()?->name ?? __('No template assigned') }}
                                        <br> <small><i class="mdi mdi-checkbox-marked-circle-outline"></i>
                                            {{ __('Inherited by group') }} - <a
                                                href="{{ route('groups.show', $device->group->id) }}">{{ __('Go to group') }}</a></small>
                                    </p>
                                @elseif($device->presentationFromSchedule())
                                    <p><b>{{ __('Assigned template') }}:</b>
                                        {{ $device->getPresentation()?->name ?? __('No template assigned') }}
                                        <br> <small><i class="mdi mdi-checkbox-marked-circle-outline"></i>
                                            {{ __('Inherited by schedule') }} - <a
                                                href="{{ route('schedules.show', $device->getPresentationId()) }}">{{ __('Go to schedule') }}</a></small>
                                    </p>
                                @else
                                    <div class="field">
                                        <label class="label">{{ __('Assigned template') }}</label>
                                        <div class="select is-fullwidth">
                                            <select name="presentation_id">
                                                <option value="">{{ __('No template assigned') }}</option>
                                                @foreach ($presentations as $presentation)
                                                    <option @if ($device->getPresentationId() == $presentation->id) selected @endif
                                                        value="{{ $presentation->id }}"
                                                        @if ($presentation->id == $device->getPresentationId()) selected @endif>{{ $presentation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <label class="label">&nbsp;</label>
                                <button type="submit" class="button is-primary">{{ __('Save') }}</button>
                            </form>
                        @endcan
                    </div>
                </div>
            @endcan
            <hr>

            <div class="pt-5">
                <div class="columns">
                    @can('read devices')
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
                    @endcan
                </div>

                @can('read devices')
                    @can('register devices')
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
                                                <p><b>{!! __('Open :url on device and enter following key', [
                                                    'url' => '<a href="' . url(route('devices.register')) . '">' . url(route('devices.register')) . '</a>',
                                                ]) !!}:</b></p>
                                                <b><code>{{ $device->secret }}</code></b>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                @endcan
            </div>
        </div>
    </div>
@endsection
