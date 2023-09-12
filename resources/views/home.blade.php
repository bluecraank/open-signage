@extends('layouts.app')

@section('content')
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
                        <th>Name</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Presentation') }}</th>
                        <th>{{ __('Current slide') }}</th>
                        <th>{{ __('Last seen') }}</th>
                        <th>{{ __('Last update') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($devices as $device)
                        <tr>
                            <td>{{ $device->name }}</td>
                            <td>{{ $device->description }}</td>
                            <td>{{ $presentation[$device->presentation_id]['name'] ?? 'Keine Vorlage zugewiesen' }}</td>
                            <td>
                                @php
                                    $slides = $device->presentation?->slides;
                                    $currentSlide = $slides?->toArray()[$device->current_slide ?? 0];
                                    $name = $currentSlide['name'] ?? 'N/A';
                                    $preview = $currentSlide['publicpreviewpath'] ?? '/data/img/placeholder.png';
                                    $countSlides = $device->presentation?->slides->count();
                                @endphp
                                <div class="dropdown is-hoverable">
                                    <div class="dropdown-trigger">
                                        <button class="button is-small">
                                            <span>{{ $name }} ({{ $device->current_slide+1 }}/{{ $countSlides }})</span>
                                            <span class="icon is-small">
                                                <i class="mdi mdi-chevron-down" aria-hidden="true"></i>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-content">
                                            <div class="dropdown-item">
                                                <img src="{{ $preview }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ Carbon::parse($device->last_seen)?->diffForHumans() ?? ($device->registered ? __('Registered') : __('Waiting for registration...')) }}
                            <td>{{ Carbon::parse($device->startup_timestamp)?->diffForHumans() ?? 'N/A' }}</td>
                            <td>

                                <form action="{{ route('devices.destroy', ['id' => $device->id]) }}" method="POST"
                                    onsubmit="return confirm('Soll dieses Gerät gelöscht werden?')">
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
