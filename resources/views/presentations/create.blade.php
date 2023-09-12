@extends('layouts.app')

@section('content')
    <div class="title">
        {{ __('Create template') }}
    </div>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Create template') }}
            </p>
        </header>

        <div class="card-content">
            <form action="{{ route('presentations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="field">
                    <label class="label">{{ __('Template name') }}</label>
                    <input required type="text" class="input" name="name" placeholder="{{ __('Template name') }}">
                </div>

                <div class="field">
                    <label class="label">{{ __('Description') }}</label>
                    <input required type="text" class="input" name="description" placeholder="{{ __('Description') }}">
                </div>

                <div class="field">
                    <label for="" class="label">{{ __('Upload pdf') }}</label>
                    <div class="file has-name" id="file-upload">
                        <label class="file-label">
                            <input class="file-input" type="file" name="file" accept=".pdf">
                            <span class="file-cta">
                                <span class="file-icon">
                                    <i class="mdi mdi-upload"></i>
                                </span>
                                <span class="file-label">
                                    {{ __('Select pdf') }}…
                                </span>
                            </span>
                            <span class="file-name">
                                {{ __('No file selected') }}
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

                <button type="submit" class="button is-primary is-small">{{ __('Save') }}</button>
                <button type="reset" class="button is-danger is-pulled-right is-small">{{ __('Reset') }}</button>
            </form>
        </div>
    </div>
@endsection
