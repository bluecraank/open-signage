@extends('layouts.app')

@section('content')
    <div class="card has-table">
        <header class="card-header">
            <p class="card-header-title">
                Präsentationsvorlagen
            </p>

            <div class="card-header-actions">
                <a href="/presentations/create" class="button is-primary is-small">
                    <span class="icon"><i class="mdi mdi-plus"></i></span>
                    <span>Vorlage hinzufügen</span>
                </a>
            </div>
        </header>

        <div class="card-content">
            <table class="table is-fullwidth">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Genutzt von</th>
                        <th>Beschreibung</th>
                        <th>Erstellt von</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($presentations as $presentation)
                        <tr>
                            <td>{{ $presentation->name }}</td>
                            <td>{{ $presentation->devices->count() }} {{ trans_choice('Gerät|Geräte', $presentation->devices->count()) }}</td>
                            <td>{{ $presentation->description }}</td>
                            <td>{{ $presentation->author }}</td>
                            <td>

                                <form action="{{ route('presentations.destroy', ['id' => $presentation->id]) }}" method="POST" onsubmit="return confirm('Soll diese Präsentationsvorlage gelöscht werden?')">
                                    @method('DELETE')
                                    @csrf
                                    <a class="button is-info is-small" href="{{ route('presentations.update', ['id' => $presentation->id]) }}"><i class="mdi mdi-pen"></i></a>
                                    <button class="button is-danger is-small" type="submit"><i class="mdi mdi-trash-can"></i></a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if($presentations->count() == 0)
                        <tr>
                            <td colspan="5" class="has-text-centered">Keine Präsentationsvorlagen vorhanden.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
