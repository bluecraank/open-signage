@extends('layouts.app')

@section('content')
    @can('create schedules')
        @livewire('schedule-assistant')
    @endcan
    @cannot('create schedules')
        @include('unauthorized')
    @endcannot
@endsection
