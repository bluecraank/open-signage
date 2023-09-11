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
                Präsentationsvorlage erstellen
            </p>
        </header>

        <div class="card-content">
            <form action="{{ route('presentations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="field">
                    <label class="label">Vorlagenname</label>
                    <input required type="text" class="input" name="name" placeholder="Vorlagenname">
                </div>

                <div class="field">
                    <label class="label">Beschreibung</label>
                    <input required type="text" class="input" name="description" placeholder="Beschreibung">
                </div>

                <div class="field">
                    <label class="label">PDF Datei hochladen (Slides)</label>
                    <input class="" type="file" name="file" accept=".pdf">
                    {{-- <div class="file">
                        <label class="file-label">
                            <span class="file-cta">
                                <span class="file-icon">
                                    <i class="mdi mdi-upload"></i>
                                </span>
                                <span class="file-label">
                                    Choose a file…
                                </span>
                            </span>
                        </label>
                    </div> --}}
                </div>

                <button type="submit" class="button is-primary is-small">Erstellen</button>
                <button type="reset" class="button is-danger is-pulled-right is-small">Zurücksetzen</button>
            </form>
        </div>
    </div>
@endsection
