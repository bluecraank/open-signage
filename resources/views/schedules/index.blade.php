@extends('layouts.app')

@section('title', __('Schedules'))

@section('content')
    @can('read schedules')
        <x-page-header :title="__('Schedules')" :subtitle="__('Templates that are shown for a limited time')">
            <x-slot:actions>
                @can('create schedules')
                    <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('Create schedule') }}
                    </a>
                @endcan
            </x-slot:actions>
        </x-page-header>

        @foreach ([
            [__('Running schedules'), $activeSchedules, 'status-online', __('Active'), true],
            [__('Upcoming schedules'), $upcomingSchedules, 'status-pending', __('Upcoming'), true],
            [__('Past schedules'), $pastSchedules, 'status-neutral', __('Ended'), false],
        ] as [$heading, $schedules, $statusClass, $statusLabel, $editable])
            <div class="card section">
                <div class="card-header">
                    <span>{{ $heading }}</span>
                    <span class="badge rounded-pill count-badge">{{ $schedules->count() }}</span>
                </div>
                @if ($schedules->isEmpty())
                    <div class="text-muted small px-4 py-3">{{ __('No schedules found') }}</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="ps-4">Name</th>
                                    <th>{{ __('Template') }}</th>
                                    <th>{{ __('Period') }}</th>
                                    <th>{{ __('Applies to') }}</th>
                                    <th>{{ __('Created by') }}</th>
                                    <th class="pe-4 text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $schedule)
                                    <tr>
                                        <td class="ps-4">
                                            @if ($editable)
                                                <a class="table-link" href="{{ route('schedules.show', ['id' => $schedule->id]) }}">{{ $schedule->name }}</a>
                                            @else
                                                <span class="fw-semibold">{{ $schedule->name }}</span>
                                            @endif
                                            <div><span class="status {{ $statusClass }} mt-1">{{ $statusLabel }}</span></div>
                                        </td>
                                        <td>{{ $schedule->presentation?->name ?? '–' }}</td>
                                        <td class="text-nowrap">
                                            {{ $schedule->startDate() }}<br>
                                            <span class="text-muted">{{ $schedule->endDate() }}</span>
                                        </td>
                                        <td class="text-muted">{{ $schedule->appliesTo() }}</td>
                                        <td class="text-muted">{{ $schedule->created_by }}</td>
                                        <td class="actions-cell pe-4">
                                            @if ($editable)
                                                <a class="btn btn-outline-secondary btn-sm icon-btn" title="{{ __('Update schedule') }}"
                                                    href="{{ route('schedules.show', ['id' => $schedule->id]) }}"><i class="bi bi-pencil"></i></a>
                                            @endif
                                            @can('delete schedules')
                                                <x-delete-button :action="route('schedules.destroy', ['id' => $schedule->id])"
                                                    :confirm="__('Are you sure to delete this schedule?')" />
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endforeach
    @endcan
    @cannot('read schedules')
        @include('unauthorized')
    @endcannot
@endsection
