@extends('layouts.app')

@section('content')
    <script src="/data/js/jquery-3.7.1.min.js"></script>
    <script src="/data/js/jquery.multi-select.js"></script>
    <link rel="stylesheet" href="/data/css/multi-select.css ">
    <script>
        $(document).ready(function() {
            $('#multiselect').multiSelect({
                'selectableHeader': '<div class="text-center has-text-weight-bold">{{ __('Selectable') }}</div>',
                'selectionHeader': '<div class="text-center has-text-weight-bold">{{ __('Selected') }}</div>'
            });
        });
    </script>

    <h3 class="mb-3">{{ __('Groups') }}</h3>

    <div class="card">
        <h5 class="card-header">{{ __('Create group') }}</h5>
        <div class="card-body">

            <form action="{{ route('groups.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                    <input required type="text" class="form-control" name="name" placeholder="Name" id="name">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Template') }}<span class="text-danger">*</span></label>
                    <select required class="form-select" name="presentation_id">
                        <option value="0">{{ __('Select a template') }}...</option>
                        @foreach ($presentations as $presentation)
                            <option value="{{ $presentation->id }}" @if (!$presentation->processed) disabled @endif>
                                {{ $presentation->name }} @if (!$presentation->processed)
                                    ({{ __('In process') }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Devices') }}<span class="text-danger">*</span></label>
                    <select name="devices[]" id="multiselect" multiple="multiple">
                        @foreach ($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->description }} ({{ $device->name }})</option>
                        @endforeach
                    </select>
                </div>

                <hr>

                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                <button type="reset" class="btn btn-danger">{{ __('Reset') }}</button>
            </form>
        </div>
    </div>
@endsection
