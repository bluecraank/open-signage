@extends('layouts.app')

@section('content')
    <div class="card has-table">
        <header class="card-header">
            <p class="card-header-title">
                Geräte

            </p>

            <div class="card-header-actions">
                <a href="/devices/create" class="button is-primary is-small">
                    <span class="icon"><i class="mdi mdi-plus"></i></span>
                    <span>Gerät hinzufügen</span>
                </a>
            </div>
        </header>

        <div class="card-content">
            <table class="table is-fullwidth">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>IP</th>
                        <th>Beschreibung</th>
                        <th>Präsentation</th>
                        <th>Zuletzt gesehen</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($devices as $device)
                        <tr>
                            <td>{{ $device->name }}</td>
                            <td>{{ $device->ip_address }}</td>
                            <td>{{ $device->description }}</td>
                            <td>{{ ($presentation[$device->presentation_id]['name']) ?? 'Keine Vorlage zugewiesen' }}</td>
                            <td>{{ $device->last_seen ?? ($device->registered ? 'Registriert...' : 'Warte auf Registrierung...') }}
                                @if(!$device->registered) Key: <span class="blur secret">{{ $device->secret }}</span> @endif
                            </td>
                            <td>

                                <form action="{{ route('devices.destroy', ['id' => $device->id]) }}" method="POST" onsubmit="return confirm('Soll dieses Gerät gelöscht werden?')">
                                    @method('DELETE')
                                    @csrf
                                    <a class="button is-info is-small" href="{{ route('devices.update', ['id' => $device->id]) }}"><i class="mdi mdi-pen"></i></a>
                                    <button class="button is-danger is-small" type="submit"><i class="mdi mdi-trash-can"></i></a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
