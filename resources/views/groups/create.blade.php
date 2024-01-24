@extends('layouts.app')

@section('content')
    <script src="/data/js/jquery-3.7.1.min.js"></script>
    <script src="/data/js/jquery.multi-select.js"></script>
    <link rel="stylesheet" href="/data/css/multi-select.css ">
    <script>
        $(document).ready(function() {
            $('#multiselect').multiSelect({
                'selectableHeader': '<div class="has-text-centered has-text-weight-bold">{{ __('Selectable') }}</div>',
                'selectionHeader': '<div class="has-text-centered has-text-weight-bold">{{ __('Selected') }}</div>'
            });
        });
    </script>
    <div class="title">{{ __('Create group') }}</div>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Create group') }}
            </p>
        </header>

        <div class="card-content">
            <form action="{{ route('groups.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label class="label">Name<span class="has-text-danger">*</span></label>
                    <input required type="text" class="input" name="name" placeholder="Name">
                </div>

                <div class="field">
                    <label class="label">{{ __('Template') }}<span class="has-text-danger">*</span></label>
                    <div class="select is-fullwidth">
                        <select required name="presentation_id">
                            <option value="0">{{ __('Select a template') }}...</option>
                            @foreach ($presentations as $presentation)
                            <option value="{{ $presentation->id }}"
                                @if (!$presentation->processed) disabled @endif>{{ $presentation->name }} @if (!$presentation->processed) ({{ __('In process') }}) @endif
                            </option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="field mb-5">
                    <label class="label">{{ __('Devices') }}<span class="has-text-danger">*</span></label>
                    <select name="devices[]" id="multiselect" multiple="multiple">
                        @foreach ($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->description }} ({{ $device->name }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="button is-primary">{{ __('Save') }}</button>
                <button type="reset" class="button is-danger is-light">{{ __('Reset') }}</button>
            </form>
        </div>
    </div>
@endsection
