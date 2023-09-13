@extends('layouts.app')

@section('content')
    <div class="title">{{ __('Devices') }}</div>

    <div class="card has-table">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Devices') }}
            </p>

            <div class="card-header-actions">
                <a href="/devices/create" class="button is-primary is-small">
                    <span class="icon"><i class="mdi mdi-plus"></i></span>
                    <span>{{ __('Create device') }}</span>
                </a>
            </div>
        </header>

        <div class="card-content">
            <table class="table is-fullwidth">
                <thead>
                    <tr>
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
                            <td>
                                @php
                                    $slides = $device->presentation?->slides;
                                    $currentSlide = $slides?->toArray()[$device->current_slide ?? 0];
                                    $preview = $currentSlide['publicpreviewpath'] ?? '/data/img/placeholder.png';
                                @endphp
                                <img width="150" src="{{ $preview }}" alt="">
                            </td>
                            <td>{{ $device->name }}</td>
                            <td>{{ $device->description }}</td>
                            <td>{{ $presentation[$device->presentation_id]['name'] ?? 'Keine Vorlage zugewiesen' }}</td>
                            <td>@if($device->last_seen) {{ Carbon::parse($device->last_seen)->diffForHumans() }} @else {{ ($device->registered ? __('Registered') : __('Waiting for registration...')) }} @endif</td>
                            <td>@if($device->startup_timestamp) {{ Carbon::parse($device->startup_timestamp)->diffForHumans() }} @else N/A @endif</td>
                            <td>

                                <form action="{{ route('devices.destroy', ['id' => $device->id]) }}" method="POST"
                                    onsubmit="return confirm('{{ __('Are you sure to delete this device?') }}')">
                                    @method('DELETE')
                                    @csrf
                                    <a class="button is-info is-small"
                                        href="{{ route('devices.update', ['id' => $device->id]) }}"><i
                                            class="mdi mdi-pen"></i></a>
                                    <button class="button is-danger is-small" type="submit"><i
                                            class="mdi mdi-trash-can"></i></a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if($devices->count() == 0)
                        <tr>
                            <td colspan="7" class="has-text-centered">{{ __('No devices found, please create one first') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
