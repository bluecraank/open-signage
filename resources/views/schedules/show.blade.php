@extends('layouts.app')

@section('title', $schedule->name)

@section('content')
    @can('update schedules')
        <x-page-header :title="$schedule->name" :subtitle="$schedule->startDate() . ' – ' . $schedule->endDate()"
            :back="route('schedules.index')" :backLabel="__('Schedules')">
            <x-slot:actions>
                @can('delete schedules')
                    <x-delete-button size="md" :action="route('schedules.destroy', ['id' => $schedule->id])"
                        :confirm="__('Are you sure to delete this schedule?')" :label="__('Delete')" />
                @endcan
            </x-slot:actions>
        </x-page-header>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title-sm">{{ __('Preview') }}</div>
                        @php
                            // Presentation can have no slides (e.g. still processing)
                            $preview = $schedule->presentation?->slides->first()?->publicpreviewpath ?? config('app.placeholder_image');
                        @endphp
                        @if ($schedule->presentation)
                            <a href="{{ route('presentations.show', $schedule->presentation->id) }}">
                                <img class="screen" src="{{ $preview }}" alt="">
                            </a>
                            <a class="d-inline-block mt-3 small" href="{{ route('presentations.show', $schedule->presentation->id) }}">
                                {{ $schedule->presentation->name }} <i class="bi bi-arrow-right"></i></a>
                        @else
                            <img class="screen" src="{{ $preview }}" alt="">
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">{{ __('Update schedule') }}</div>
                    <div class="card-body p-4">
                        @include('schedules.form', ['schedule' => $schedule])
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @cannot('update schedules')
        @include('unauthorized')
    @endcannot
@endsection
