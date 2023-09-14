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
                    @can('update groups')
                        <div class="column">
                            <form action="{{ route('groups.update', $group->id) }}" method="post">
                                @method('PUT')
                                @csrf
                                <div class="field">
                                    <label class="label">Name</label>
                                    <input class="input" type="text" name="name" value="{{ $group->name }}" />
                                </div>

                                <div class="field">
                                    <label class="label">{{ __('Assigned template') }}</label>
                                    <div class="select is-fullwidth">
                                        <select name="presentation_id" id="">
                                            <option value="0">{{ __('Select a template') }}...</option>
                                            @foreach ($presentations as $presentation)
                                                <option @if ($presentation->id == $group->presentation_id) selected @endif
                                                    value="{{ $presentation->id }}">{{ $presentation->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">{{ __('Devices') }}</label>
                                    <select multiple="multiple" name="devices[]" id="multiselect">
                                        @foreach ($devices as $device)
                                            <option @if($device->group_id == $group->id) selected @endif value="{{ $device->id }}">{{ $device->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <label class="label">&nbsp;</label>
                                <button type="submit" class="button is-primary">{{ __('Save') }}</button>
                            </form>
                            @can('delete groups')
                                <form class="pt-2" action="{{ route('groups.destroy', $group->id) }}" method="POST"
                                    onsubmit="return confirm('{{ __('Are you sure to delete this group?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button is-danger is-smalls">{{ __('Delete') }}</button>
                                </form>
                            @endcan
                        </div>
                    @endcan
                @endcan
            </div>
        </div>
    </div>
@endsection
