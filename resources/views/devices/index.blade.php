@extends('layouts.app')

@section('content')
    @can('read devices')
    <div class="title">{{ __('Devices') }}</div>

    <div class="card has-table">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Devices') }}
            </p>

            <div class="card-header-actions">
                @can('create devices')
                    <a href="{{ route('devices.create') }}" class="button is-primary is-small">
                        <span class="icon"><i class="mdi mdi-plus"></i></span>
                        <span>{{ __('Create device') }}</span>
                    </a>
                @endcan
            </div>
        </header>

        <div class="card-content">
            <table class="table is-narrow is-striped is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th></th>
                        <th>{{ __('Current slide') }}</th>
                        <th>Name</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Presentation') }}</th>
                        <th>{{ __('Last connection') }}</th>
                        <th>{{ __('Last monitor reload') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($devices as $device)
                        <tr>
                            <td style="text-align: center;vertical-align:middle;">
                                {{ $device->isActive() }}
                            </td>
                            <td>
                                @php
                                    $device_pres = $device->getPresentation();
                                    $slides = $device_pres?->slides;
                                    $currentSlide = $slides?->toArray()[$device->current_slide ?? 0];
                                    $preview = $currentSlide['publicpreviewpath'] ?? '/data/img/placeholder.png';
                                @endphp
                                <img width="150" src="{{ $preview }}" alt="">
                            </td>
                            <td>{{ $device->name }}</td>
                            <td>{{ $device->description }}</td>
                            <td>{{ $device_pres?->name ?? __('No template assigned') }} @if($device->presentationFromGroup()) <br> <small class="has-text-success"><i class="mdi mdi-checkbox-marked-circle-outline"></i> {{ __('Inherited by group') }}</small> @endif</td>
                            <td>
                                @if ($device->last_seen)
                                    {{ Carbon::parse($device->last_seen)->diffForHumans() }}
                                @else
                                    {{ $device->registered ? __('Registered') : __('Waiting for registration...') }}
                                @endif
                            </td>
                            <td>
                                @if ($device->startup_timestamp)
                                    {{ Carbon::parse($device->startup_timestamp)->diffForHumans() }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="actions-cell">

                                <form action="{{ route('devices.destroy', ['id' => $device->id]) }}" method="POST"
                                    onsubmit="return confirm('{{ __('Are you sure to delete this device?') }}')">
                                    @method('DELETE')
                                    @csrf
                                    @can('read devices')
                                        <a class="button is-info is-small"
                                            href="{{ route('devices.update', ['id' => $device->id]) }}"><i
                                                class="mdi mdi-pen"></i></a>
                                    @endcan
                                    @can('delete devices')
                                        <button class="button is-danger is-small" type="submit"><i
                                                class="mdi mdi-trash-can"></i></button>
                                    @endcan
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($devices->count() == 0)
                        <tr>
                            <td colspan="8" class="has-text-centered">
                                {{ __('No devices found') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endcan

    @cannot('read devices')
        @include('unauthorized')
    @endcannot
@endsection
