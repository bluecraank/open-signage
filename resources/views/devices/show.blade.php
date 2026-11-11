@extends('layouts.app')

@section('title', $device->name)

@section('content')
    @php
        $presentation = $device->getPresentation();
        $active = $device->isActive();
    @endphp

    <x-page-header :title="$device->name" :subtitle="$device->description" :back="route('devices.index')" :backLabel="__('Devices')">
        <x-slot:actions>
            @can('register devices')
                @if (!$device->registered)
                    <form action="{{ route('devices.register.accept') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $device->id }}">
                        <button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>{{ __('Accept and register monitor') }}</button>
                    </form>
                @endif
            @endcan
            @can('force reload monitor')
                <form method="POST" action="{{ route('devices.reload', ['id' => $device->id]) }}">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-outline-secondary" type="submit" name="reload" value="force_reload">
                        <i class="bi bi-arrow-clockwise me-1"></i>{{ __('Force page reload') }}</button>
                </form>
            @endcan
            @can('delete devices')
                <x-delete-button size="md" :action="route('devices.destroy', ['id' => $device->id])"
                    :confirm="__('Are you sure to delete this device?')" :label="__('Delete device')" />
            @endcan
        </x-slot:actions>
    </x-page-header>

    @if (!$device->registered)
        <div class="alert alert-warning"><i class="bi bi-hourglass-split"></i>
            <div>{{ __('Please accept this monitor to register it') }}</div>
        </div>
    @endif

    @if ($device->force_reload)
        <div class="alert alert-info"><i class="bi bi-arrow-clockwise"></i>
            <div>{{ __('This device will reload the page on next connection') }}</div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <div class="card-title-sm">{{ __('Current slide') }}</div>
                    <img class="screen" src="{{ $device->currentSlidePreview() }}" alt="">
                    <div class="d-flex justify-content-between align-items-start mt-3">
                        <x-device-status :device="$device" :active="$active" />
                        @if ($device->group?->synchronized)
                            <span class="source-hint"><i class="bi bi-arrow-repeat"></i> {{ __('Synchronize monitors') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">{{ __('Edit device') }}</div>
                <div class="card-body">
                    @can('update devices')
                        <form action="{{ route('devices.update', $device->id) }}" method="post">
                            @method('PUT')
                            @csrf
                    @endcan

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="device-name">Name</label>
                            <input class="form-control" id="device-name" type="text" required name="name" value="{{ $device->name }}"
                                @cannot('update devices') disabled @endcannot @cannot('create devices') readonly @endcannot />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="device-location">{{ __('Location') }}</label>
                            <input class="form-control" id="device-location" type="text" required name="description"
                                value="{{ $device->description }}" @cannot('update devices') disabled @endcannot
                                @cannot('create devices') readonly @endcannot />
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="device-template">{{ __('Assigned template') }}</label>
                            @if ($device->presentationFromSchedule() || $device->presentationFromGroup() || !auth()->user()->can('update devices'))
                                <div class="form-control-plaintext py-0">
                                    {{ $presentation?->name ?? __('No template assigned') }}
                                    @if ($presentation)
                                        <a class="ms-1 small" href="{{ route('presentations.show', $presentation->id) }}">{{ __('Go to presentation') }}</a>
                                    @endif
                                </div>
                                @if ($device->presentationFromSchedule())
                                    <div class="source-hint"><i class="bi bi-calendar-check"></i> {{ __('Inherited by schedule') }} ·
                                        <a href="{{ route('schedules.show', $device->activeSchedule()->id) }}">{{ __('Go to schedule') }}</a></div>
                                @elseif ($device->presentationFromGroup())
                                    <div class="source-hint"><i class="bi bi-collection"></i> {{ __('Inherited by group') }} ·
                                        <a href="{{ route('groups.show', $device->group->id) }}">{{ __('Go to group') }}</a></div>
                                @endif
                            @else
                                <select class="form-select" id="device-template" name="presentation_id">
                                    <option value="">{{ __('No template assigned') }}</option>
                                    @foreach ($presentations as $option)
                                        <option @selected($device->getPresentationId() == $option->id) value="{{ $option->id }}"
                                            @disabled(!$option->processed)>
                                            {{ $option->name }} @if (!$option->processed) ({{ __('In process') }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    @can('update devices')
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            </div>
                        </form>
                    @endcan
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">{{ __('Details') }}</div>
                <div class="card-body">
                    <dl class="meta-list">
                        <div>
                            <dt>{{ __('IP Address') }}</dt>
                            <dd>{{ $device->ip_address }}</dd>
                        </div>
                        <div>
                            <dt>{{ __('Last connection') }}</dt>
                            <dd>{{ $device->last_seen ? Carbon::parse($device->last_seen)->diffForHumans() : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt>{{ __('Last monitor reload') }}</dt>
                            <dd>{{ $device->startup_timestamp ? Carbon::parse($device->startup_timestamp)->diffForHumans() : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt>{{ __('Group') }}</dt>
                            <dd>
                                @if ($device->group)
                                    <a href="{{ route('groups.show', $device->group->id) }}">{{ $device->group->name }}</a>
                                @else
                                    –
                                @endif
                            </dd>
                        </div>
                        @can('register devices')
                            <div class="w-100" style="grid-column: 1 / -1">
                                <dt>{{ __('Monitor URL') }}</dt>
                                <dd><a target="_blank" href="{{ url(route('devices.monitor', $device->secret)) }}">{{ url(route('devices.monitor', $device->secret)) }}</a></dd>
                            </div>
                        @endcan
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
