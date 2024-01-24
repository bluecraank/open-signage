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
    <div class="title">{{ $group->name }}</div>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Edit group') }}
            </p>

        </header>

        <div class="card-content">
            <div class="columns">
                @can('read groups')
                    <div class="column is-4">
                        @php
                            $slides = $group->presentation?->slides;
                            $currentSlide = $slides?->toArray()[$group->current_slide ?? 0];
                            $preview = $currentSlide['publicpreviewpath'] ?? config('app.placeholder_image');
                        @endphp
                        <img width="500" class="monitor-border" src="{{ $preview }}" alt="">

                        @can('delete groups')
                            <form class="pt-2" action="{{ route('groups.destroy', $group->id) }}" method="POST"
                                onsubmit="return confirm('{{ __('Are you sure to delete this group?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button is-danger is-smalls">{{ __('Delete group') }}</button>
                            </form>
                        @endcan
                    </div>
                    <div class="column">
                        <div class="columns">
                            @cannot('update groups')
                                <div class="column">
                                    <p><b>Name:</b> {{ $group->name }}</p>
                                    <p><b>{{ __('Devices') }}:</b> {{ $group->devices->count() }}</p>
                                    <p><b>{{ __('Assigned template') }}:</b>
                                        {{ $group->presentation?->name ?? __('No template assigned') }}</p>
                                </div>
                            @endcannot
                            @can('update groups')
                                <div class="column">
                                    <form action="{{ route('groups.update', $group->id) }}" method="post">
                                        @method('PUT')
                                        @csrf
                                        <div class="field">
                                            <label class="label">Name<span class="has-text-danger">*</span></label>
                                            <input class="input" type="text" name="name" value="{{ $group->name }}" />
                                        </div>

                                        <div class="field">
                                            <label class="label">{{ __('Assigned template') }}<span
                                                    class="has-text-danger">*</span></label>
                                            <div class="select is-fullwidth">
                                                    <select name="presentation_id">
                                                        <option value="">{{ __('No template assigned') }}</option>
                                                        @foreach ($presentations as $presentation)
                                                            <option @if ($group->presentation_id == $presentation->id) selected @endif
                                                                value="{{ $presentation->id }}"
                                                                @if (!$presentation->processed) disabled @endif>{{ $presentation->name }} @if (!$presentation->processed) ({{ __('In process') }}) @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                                                            </div>
                                        </div>

                                        @can('assign device to group')
                                            <div class="field">
                                                <label class="label">{{ __('Devices') }}<span class="has-text-danger">*</span></label>
                                                <select multiple="multiple" name="devices[]" id="multiselect">
                                                    @foreach ($devices as $device)
                                                        <option @if ($device->group_id == $group->id) selected @endif
                                                            value="{{ $device->id }}">{{ $device->description }} ({{ $device->name }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endcan

                                        <label class="label">&nbsp;</label>
                                        <button type="submit" class="button is-primary">{{ __('Save') }}</button>
                                    </form>

                                </div>
                            @endcan
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
