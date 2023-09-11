@extends('layouts.app')

@section('content')
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                Geräte erstellen
            </p>
        </header>

        <div class="card-content">
            <form action="{{ route('devices.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label class="label">Gerätename</label>
                    <input required type="text" class="input" name="name" placeholder="Gerätename">
                </div>

                <div class="field">
                    <label class="label">IP-Adresse</label>
                    <input required type="text" class="input" name="ip_address" placeholder="IP-Adresse">
                </div>

                <div class="field">
                    <label class="label">Beschreibung</label>
                    <input required type="text" class="input" name="description" placeholder="Beschreibung">
                </div>

                <div class="field">
                    <label class="label">Präsentationsvorlage</label>
                    <div class="select">
                        <select required name="presentation_id" id="">
                            <option value="0">Bitte eine Vorlage wählen...</option>
                            @foreach ($presentations as $presentation)
                                <option value="{{ $presentation->id }}">{{ $presentation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="button is-primary is-small">Erstellen</button>
                <button type="reset" class="button is-danger is-pulled-right is-small">Zurücksetzen</button>
            </form>
        </div>
    </div>
@endsection
