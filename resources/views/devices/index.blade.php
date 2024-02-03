@extends('layouts.app')

@section('content')
    @can('read devices')
        <h3>{{ __('Devices') }}</h3>
        <span class="badge bg-success mb-3">{{ $devices->where('active', true)->count() }} {{ __('active') }}</span>
        <span class="badge bg-danger mb-3 ml-2">{{ $devices->where('active', false)->count() }} {{ __('offline') }}</span>
        @livewire('live-monitor-overview')
    @endcan

    @cannot('read devices')
        @include('unauthorized')
    @endcannot
@endsection
