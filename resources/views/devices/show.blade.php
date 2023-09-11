@extends('layouts.app')

@section('content')
    @if ($errors)
        @foreach ($errors->all() as $error)
            <div class="notification is-danger">
                {{ $error }}
            </div>
        @endforeach
    @endif

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                Monitor - {{ $device->name }}
            </p>

        </header>

        <div class="card-content">
            <form action="{{ route('devices.update', $device->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label for="" class="label">Vorlagenname</label>
                            <input class="input" type="text" name="name" value="{{ $device->name }}" />
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label for="" class="label">Beschreibung</label>
                            <input class="input" type="text" name="description" value="{{ $device->description }}" />
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label for="" class="label">Zugewiesene Vorlage</label>
                            <div class="select">
                                <select name="presentation_id">
                                    <option value="">Keine Vorlage zugewiesen</option>
                                    @foreach ($presentations as $presentation)
                                        <option @if ($device->presentation_id == $presentation->id) selected @endif
                                            value="{{ $presentation->id }}"
                                            @if ($presentation->id == $device->presentation_id) selected @endif>{{ $presentation->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="column">
                        <label for="" class="label">&nbsp;</label>
                        <button type="submit" class="button is-primary">Speichern</button>
                    </div>
                </div>
            </form>

            <div class="columns pt-5">
                <div class="column">
                    <div class="notification @if (!$device->registered) is-danger @else is-primary @endif">
                        Status: {{ $device->registered ? 'Registriert' : 'Nicht registriert' }}
                        @if (!$device->registered)
                            <div>Key: <span class="blur secret">{{ $device->secret }}</span></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
