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

        <div class="card">
            <div class="card-header">{{ __('Update schedule') }}</div>
            <div class="card-body p-4">
                @include('schedules.form', ['schedule' => $schedule])
            </div>
        </div>
    @endcan
    @cannot('update schedules')
        @include('unauthorized')
    @endcannot
@endsection
