@extends('layouts.app')

@section('content')
    <h3 class="mb-3">Monitor: {{ $device->name }}</h3>
    <div class="card">
        <h5 class="card-header">
            {{ __('Edit device') }}

            @can('register devices')
                @if (!$device->registered)
                    <div class="float-end">
                        <form action="{{ route('devices.register.accept') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $device->id }}">
                            <button class="btn btn-primary btn-sm">{{ __('Accept and register monitor') }}</button>
                        </form>
                    </div>
                @endif
            @endcan
        </h5>

        <div class="card-body">
            @can('read devices')
                <div class="row">
                    <div class="col-4">
                        @php
                            $slides = $device->getPresentation()?->slides;
                            if ($slides?->toArray() != null) {
                                $currentSlide = array_key_exists($device->current_slide ?? 0, $slides?->toArray()) ? $slides?->toArray()[$device->current_slide ?? 0] : $slides?->toArray()[0];
                                $preview = $currentSlide['publicpreviewpath'] ?? config('app.placeholder_image');
                            } else {
                                $preview = config('app.placeholder_image');
                            }
                        @endphp
                        <img class="monitor-border w-100" src="{{ $preview }}" alt="">

                        @can('force reload monitor')
                            <form method="POST" class="my-3" action="{{ route('devices.reload', ['id' => $device->id]) }}">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-primary btn-sm" type="submit" name="reload"
                                    value="force_reload">{{ __('Force page reload') }}</button>
                            </form>
                        @endcan

                        @can('delete devices')
                            <form method="POST" class="pt-1" action="{{ route('devices.destroy', ['id' => $device->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit"
                                    onclick="return confirm('{{ __('Are you sure to delete this device?') }}')">{{ __('Delete device') }}</button>
                            </form>
                        @endcan
                    </div>
                    <div class="col-8">
                        @cannot('update devices')
                            <div class="mb-3">
                                <p><b>Name:</b> <br> {{ $device->name }}</p>
                            </div>

                            <div class="mb-3">
                                <p><b>{{ __('Description') }}:</b> <br> {{ $device->description }}</p>
                            </div>

                            <div class="mb-3">
                                @if ($device->presentationFromGroup())
                                    <p><b>{{ __('Assigned template') }}:</b> <br>
                                        {{ $device->getPresentation()?->name ?? __('No template assigned') }}
                                        <br> <small class="text-success"><i class="bi bi-check2-circle"></i>
                                            {{ __('Inherited by group') }}</small> - <a
                                            href="{{ route('groups.show', $device->group->id) }}">{{ __('Go to group') }}</a></small>
                                    </p>
                                @elseif($device->presentationFromSchedule())
                                    <p><b>{{ __('Assigned template') }}:</b>
                                        {{ $device->getPresentation()?->name ?? __('No template assigned') }}
                                        <br> <small class="text-success"><i class="bi bi-check2-circle"></i>
                                            {{ __('Inherited by schedule') }} - <a
                                                href="{{ route('schedules.show', $device->getPresentationId()) }}">{{ __('Go to schedule') }}</a></small>
                                    </p>
                                @else
                                    <p><b>{{ __('Assigned template') }}:</b>
                                        {{ $presentation[$device->getPresentationId()]['name'] ?? __('No template assigned') }}</p>
                                @endif
                            </div>
                        </div>
                    @endcannot

                    @can('update devices')
                        <form action="{{ route('devices.update', $device->id) }}" method="post" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Name<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" required @cannot('create devices') readonly @endcannot
                                    name="name" value="{{ $device->name }}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('Location') }}<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" required @cannot('create devices') readonly @endcannot
                                    name="description" value="{{ $device->description }}" />
                            </div>

                            <div class="mb-3">

                                @if ($device->presentationFromGroup())
                                    <p><b>{{ __('Assigned template') }}:</b>
                                        {{ $device->getPresentation()?->name ?? __('No template assigned') }}
                                        <br> <small class="text-success"><i class="bi bi-check2-circle"></i>
                                            {{ __('Inherited by group') }} - <a
                                                href="{{ route('groups.show', $device->group->id) }}">{{ __('Go to group') }}</a></small>
                                    </p>
                                @elseif($device->presentationFromSchedule())
                                    <p><b>{{ __('Assigned template') }}:</b>
                                        {{ $device->getPresentation()?->name ?? __('No template assigned') }}
                                        <br> <small class="text-success"><i class="bi bi-check2-circle"></i>
                                            {{ __('Inherited by schedule') }} - <a
                                                href="{{ route('schedules.show', $device->getPresentationId()) }}">{{ __('Go to schedule') }}</a></small>
                                    </p>
                                @else
                                    <div class="field">
                                        <label class="form-label">{{ __('Assigned template') }}</label>
                                        <div class="select is-fullwidth">
                                            <select name="presentation_id">
                                                <option value="">{{ __('No template assigned') }}</option>
                                                @foreach ($presentations as $presentation)
                                                    <option @if ($device->getPresentationId() == $presentation->id) selected @endif
                                                        value="{{ $presentation->id }}"
                                                        @if (!$presentation->processed) disabled @endif>
                                                        {{ $presentation->name }}
                                                        @if (!$presentation->processed)
                                                            ({{ __('In process') }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <hr>

                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </form>
                    @endcan
                </div>
            </div>
        @endcan
        <hr>

        <div class="pt-5">
            <div class="row">
                @can('read devices')
                    <div class="col">
                        <p><b>{{ __('IP Address') }}:</b> <br> {{ $device->ip_address }}</p>
                    </div>

                    <div class="col">
                        <p><b>{{ __('Last connection') }}:</b> <br>
                            {{ Carbon::parse($device->last_seen)->diffForHumans() ?? 'N/A' }}</p>
                    </div>

                    <div class="col">
                        <p><b>{{ __('Last monitor reload') }}:</b> <br>
                            {{ Carbon::parse($device->startup_timestamp)->diffForHumans() ?? 'N/A' }}
                        </p>
                    </div>
                @endcan
            </div>

            @can('read devices')
                @can('register devices')
                    <div class="row">
                        <div class="col">
                            <p><b>{{ __('Monitor URL') }}:</b> <br> <a target="_blank"
                                    href="{{ url(route('devices.monitor', $device->secret)) }}">{{ url(route('devices.monitor', $device->secret)) }}</a>
                            </p>
                        </div>

                        <div class="col">

                        </div>
                    </div>

                    <div class="pt-5">
                        @if ($device->force_reload)
                            <div class="row">
                                <div class="col">
                                    <div class="alert alert-info">
                                        <b>{{ __('Force page reload') }}</b>
                                        <p>{{ __('This device will reload the page on next connection') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col">
                                <div
                                    class="alert @if (!$device->registered) alert-warning @else
                                        alert-primary @endif">
                                    <div class="is-justify-content-center is-align-content-center">
                                        <div class="is-flex is-justify-content-center is-align-content-center">
                                            <span class="has-text-weight-bold">Status:
                                                {{ $device->registered ? __('Successfully registered') : __('Please accept this monitor to register it') }}</span>
                                        </div>
                                    </div>
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
