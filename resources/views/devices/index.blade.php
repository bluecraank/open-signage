@extends('layouts.app')

@section('title', __('Devices'))

@section('content')
    @can('read devices')
        <x-page-header :title="__('Devices')" :subtitle="__('All monitors and what they are showing right now')">
            <x-slot:actions>
                @can('create devices')
                    <a href="{{ route('devices.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('Create device') }}
                    </a>
                @endcan
            </x-slot:actions>
        </x-page-header>

        @livewire('live-monitor-overview')
    @endcan

    @cannot('read devices')
        @include('unauthorized')
    @endcannot
@endsection
