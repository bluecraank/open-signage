@extends('layouts.app')

@section('content')
    @can('read devices')
    <div class="title">{{ __('Devices') }}</div>

    @livewire('live-monitor-overview')
    @endcan

    @cannot('read devices')
        @include('unauthorized')
    @endcannot
@endsection
