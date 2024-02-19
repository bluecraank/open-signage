@extends('layouts.app')

@section('content')
    @can('read devices')
        <h3>{{ __('Devices') }}</h3>

        @livewire('live-monitor-overview')
    @endcan

    @cannot('read devices')
        @include('unauthorized')
    @endcannot
@endsection
