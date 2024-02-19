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
    <h3 class="mb-3">{{ __('Group') }}: {{ $group->name }}</h3>
    <div class="card">
        <h5 class="card-header">
            {{ __('Edit group') }}
        </h5>

        <div class="card-body">
            <div class="row">
                @can('read groups')
                    <div class="col-4">
                        @php
                            $slides = $group->presentation?->slides;
                            $currentSlide = $slides?->toArray()[$group->current_slide ?? 0];
                            $preview = $currentSlide['publicpreviewpath'] ?? config('app.placeholder_image');
                        @endphp
                        <img class="monitor-border w-100" src="{{ $preview }}" alt="">

                        @can('delete groups')
                            <form class="pt-2" action="{{ route('groups.destroy', $group->id) }}" method="POST"
                                onsubmit="return confirm('{{ __('Are you sure to delete this group?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete group') }}</button>
                            </form>
                        @endcan
                    </div>

                    <div class="col-8">
                        @cannot('update groups')
                            <p><b>Name:</b> <br> {{ $group->name }}</p>
                            <p><b>{{ __('Devices') }}:</b> <br> {{ $group->devices->count() }}</p>
                            <p><b>{{ __('Assigned template') }}:</b> <br>
                                @if ($group->presentation)
                                    {{ $group->presentation->name }} - <a
                                        href="{{ route('presentations.show', $group->presentation->id) }}">{{ __('Go to presentation') }}</a>
                                @else
                                    {{ __('No template assigned') }}
                                @endif
                            </p>
                        @endcannot

                        @can('update groups')
                            <form action="{{ route('groups.update', $group->id) }}" method="post">
                                @method('PUT')
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" value="{{ $group->name }}" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Assigned template') }}<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="presentation_id">
                                        <option value="">{{ __('No template assigned') }}</option>
                                        @foreach ($presentations as $presentation)
                                            <option @if ($group->presentation_id == $presentation->id) selected @endif
                                                value="{{ $presentation->id }}" @if (!$presentation->processed) disabled @endif>
                                                {{ $presentation->name }}
                                                @if (!$presentation->processed)
                                                    ({{ __('In process') }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @can('assign device to group')
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Devices') }}<span class="text-danger">*</span></label>
                                        <select multiple="multiple" name="devices[]" id="multiselect">
                                            @foreach ($devices as $device)
                                                <option @if ($device->group_id == $group->id) selected @endif value="{{ $device->id }}">
                                                    {{ $device->description }} ({{ $device->name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endcan

                                <hr>

                                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            </form>
                        @endcan
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
