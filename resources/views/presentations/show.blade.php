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
                Präsentationsvorlage - {{ $presentation->name }}
            </p>

        </header>

        <div class="card-content">
            <form action="{{ route('presentations.update', $presentation->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label for="" class="label">Vorlagenname</label>
                            <input class="input" type="text" name="name" value="{{ $presentation->name }}" />
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label for="" class="label">Beschreibung</label>
                            <input class="input" type="text" name="description"
                                value="{{ $presentation->description }}" />
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label for="" class="label">Neue PDF hochladen</label>
                            <div class="file has-name" id="file-upload">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="file" accept=".pdf">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="mdi mdi-upload"></i>
                                        </span>
                                        <span class="file-label">
                                            PDF wählen…
                                        </span>
                                    </span>
                                    <span class="file-name">
                                        Keine Datei gewählt
                                    </span>
                                </label>
                            </div>

                            <script>
                                const fileInput = document.querySelector('#file-upload input[type=file]');
                                fileInput.onchange = () => {
                                    if (fileInput.files.length > 0) {
                                        const fileName = document.querySelector('#file-upload .file-name');
                                        fileName.textContent = fileInput.files[0].name;
                                    }
                                }
                            </script>
                        </div>
                    </div>

                    <div class="column">
                        <label for="" class="label">&nbsp;</label>
                        <button type="submit" class="button is-primary">Speichern</button>
                    </div>
                </div>
            </form>

            <div class="some-space"></div>

            <div class="box">
                <div class="subtitle">Geräte</div>
                <table class="table is-fullwidth">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>IP</th>
                            <th>Beschreibung</th>
                            <th>Zuletzt gesehen</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($presentation->devices as $device)
                            <tr>
                                <td>{{ $device->name }}</td>
                                <td>{{ $device->ip_address }}</td>
                                <td>{{ $device->description }}</td>
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

            <div class="slides pt-5">
                <div class="subtitle">Slides (Bilder)</div>
                @if (!$presentation->processed)
                    <div class="box" style="display:flex">
                        <button style="justify-content:center" class="button is-loading has-background-white pr-4"></button>
                        <span style="justify-content: center" class="pl-4">Bilder werden zurzeit verarbeitet - bitte
                            später versuchen</span>
                    </div>
                @endif
                @foreach ($presentation->slides as $slide)
                    <div class="slide box">
                        <div class="columns gapless">
                            <img src="{{ $slide->publicpreviewpath() }}">
                            <div class="column">
                                <div>Name: {{ $slide->name }}</div>
                                <div>Dateiname: {{ $slide->name_on_disk }} </div>
                                <div>Reihenfolge: {{ $slide->order }}</div>
                                <div>Erstellt: {{ $slide->created_at }}</div>
                                <div>&nbsp;</div>
                                <div><a target="_blank" href="{{ $slide->publicpath() }}">Vorschau</a></div>
                            </div>

                            <div class="column">

                            </div>

                            <form action="{{ route('slides.destroy', $slide->id) }}" method="POST" onsubmit="return confirm('Soll dieser Slide wirklich gelöscht werden?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button is-danger is-small"><i class="mdi mdi-trash-can"></i></button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
