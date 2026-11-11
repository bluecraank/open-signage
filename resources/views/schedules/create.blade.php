@extends('layouts.app')

@section('title', __('Create schedule'))

@section('content')
    @can('create schedules')
        <x-page-header :title="__('Create schedule')" :subtitle="__('Show a template for a limited time')"
            :back="route('schedules.index')" :backLabel="__('Schedules')" />

        <div class="card">
            <div class="card-body p-4">
                @include('schedules.form', ['schedule' => null])
            </div>
        </div>
    @endcan
    @cannot('create schedules')
        @include('unauthorized')
    @endcannot
@endsection
