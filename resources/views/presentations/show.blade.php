@extends('layouts.app')

@section('title', $presentation->name)

@section('content')
    @php
        $firstSlide = $presentation->slides->first();
        $schedules = $presentation->getSchedules();
    @endphp

    <x-page-header :title="$presentation->name" :subtitle="__('Template') . ' · ' . $presentation->author"
        :back="route('presentations.index')" :backLabel="__('Templates')">
        <x-slot:actions>
            @can('delete presentations')
                <x-delete-button size="md" :action="route('presentations.destroy', $presentation->id)"
                    :confirm="__('Are you sure to delete this template?')" :label="__('Delete template')" />
            @endcan
        </x-slot:actions>
    </x-page-header>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <div class="card-title-sm">{{ __('Preview') }}</div>
                    @if ($firstSlide?->type === 'video')
                        <video class="screen" src="{{ $firstSlide->publicpath() }}" controls muted preload="metadata"></video>
                    @else
                        <img class="screen" src="{{ $firstSlide?->publicpreviewpath() ?? config('app.placeholder_image') }}" alt="">
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header">{{ __('Edit template') }}</div>
                <div class="card-body">
                    @cannot('update presentations')
                        <dl class="meta-list">
                            <div>
                                <dt>Name</dt>
                                <dd>{{ $presentation->name }}</dd>
                            </div>
                            <div>
                                <dt>{{ __('Slides') }}</dt>
                                <dd>{{ $presentation->slides->count() }}</dd>
                            </div>
                            <div>
                                <dt>{{ __('Used by') }}</dt>
                                <dd>{{ $presentation->devices->count() }} {{ trans_choice('Device|Devices', $presentation->devices->count()) }}</dd>
                            </div>
                        </dl>
                    @endcannot

                    @can('update presentations')
                        <form action="{{ route('presentations.update', $presentation->id) }}" method="post"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="mb-4">
                                <label class="form-label" for="inputDescription">{{ __('Description') }}<span class="text-danger">*</span></label>
                                <input class="form-control" id="inputDescription" type="text" name="name"
                                    value="{{ $presentation->name }}" required />
                            </div>

                            <label class="form-label">{{ __('Replace file') }}</label>
                            @include('partials.file-upload')

                            <div class="form-actions">
                                <button @disabled(!$presentation->processed) type="submit" class="btn btn-primary">
                                    {{ __('Save') }} @if (!$presentation->processed) ({{ __('In process') }}) @endif
                                </button>
                            </div>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-0">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <span><i class="bi bi-display me-1"></i>{{ __('Devices') }}</span>
                    <span class="badge rounded-pill count-badge">{{ $presentation->devices->count() }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($presentation->devices as $device)
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                            <div>
                                <a class="table-link" href="{{ route('devices.show', $device->id) }}">{{ $device->name }}</a>
                                <div class="small text-muted">{{ $device->description }}</div>
                            </div>
                            <span class="small text-muted">{{ $device->presentationFromGroup() ? __('By group') : __('Directly') }}</span>
                        </li>
                    @empty
                        <li class="list-group-item bg-transparent text-muted small">{{ __('No devices assigned') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <span><i class="bi bi-collection me-1"></i>{{ __('Groups') }}</span>
                    <span class="badge rounded-pill count-badge">{{ $presentation->groups->count() }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($presentation->groups as $group)
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                            <a class="table-link" href="{{ route('groups.show', $group->id) }}">{{ $group->name }}</a>
                            <span class="small text-muted">{{ $group->devices->count() }} {{ trans_choice('Device|Devices', $group->devices->count()) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item bg-transparent text-muted small">{{ __('No groups assigned') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <span><i class="bi bi-calendar-week me-1"></i>{{ __('Schedules') }}</span>
                    <span class="badge rounded-pill count-badge">{{ $schedules->count() }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($schedules as $schedule)
                        <li class="list-group-item bg-transparent">
                            <a class="table-link" href="{{ route('schedules.show', $schedule->id) }}">{{ $schedule->name }}</a>
                            <div class="small text-muted">{{ $schedule->startDate() }} – {{ $schedule->endDate() }}</div>
                        </li>
                    @empty
                        <li class="list-group-item bg-transparent text-muted small">{{ __('No schedules upcoming or active with this presentation') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="mt-4">
        @if (!$presentation->processed)
            @livewire('show-slides', ['presentation' => $presentation])
        @else
            @include('livewire.show-slides')
        @endif
    </div>
@endsection
