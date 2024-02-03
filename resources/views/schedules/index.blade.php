@extends('layouts.app')

@section('content')
    @can('read schedules')
        <h3 class="mb-3">{{ __('Schedules') }}</h3>

        <div class="card">
            <h5 class="card-header">
                {{ __('Running schedules') }}

                <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-sm float-end">
                    <i class="bi-plus"></i>
                    {{ __('Create schedule') }}
                </a>
            </h5>
            <div class="card-body">

            </div>
        </div>

        <div class="card mt-5">
            <h5 class="card-header">
                {{ __('Upcoming schedules') }}
            </h5>
            <div class="card-body">

            </div>
        </div>

        <div class="card mt-5">
            <h5 class="card-header">
                {{ __('Past schedules') }}
            </h5>
            <div class="card-body">

            </div>
        </div>
    @endcan
    @cannot('read schedules')
        @include('unauthorized')
    @endcannot
@endsection
