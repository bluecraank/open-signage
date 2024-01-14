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
                    <label class="label">{{ __('Description') }}<span class="has-text-danger">*</span></label>
                    <input required type="text" class="input" name="name" placeholder="{{ __('Description') }}">
                </div>

                <div class="field">
                    <label class="label">{{ __('Upload file') }}<span class="has-text-danger">*</span></label>
                    <div id="drop_zone" ondrop="window.dropHandler(event)" ondragover="window.dragOverHandler(event)">

                        <div style="display: inline-block" class="file has-name is-normal" id="file-upload">
                            <label class="file-label">
                                <input class="file-input" type="file" name="file" required
                                    accept="application/pdf,video/mp4">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <i class="mdi mdi-upload"></i>
                                    </span>
                                    <span class="file-label">
                                        {{ __('Select file') }}…
                                    </span>
                                </span>
                                <span class="file-name">
                                    {{ __('No file selected') }}
                                </span>
                            </label>
                        </div>
                        <p class="ml-5" style="display: inline-block">{{ __('or drag and drop to upload') }}</p>
                    </div>
                </div>

                <button type="submit" class="button is-primary">{{ __('Create') }}</button>
                <button type="reset" class="button is-danger is-light">{{ __('Reset') }}</button>
            </form>
        </div>
    </div>
@endsection
