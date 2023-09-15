@extends('layouts.app')

@section('content')
    @can('create schedules')
        <script src="/data/js/jquery-3.7.1.min.js"></script>
        <script src="/data/js/jquery.multi-select.js"></script>
        <link rel="stylesheet" href="/data/css/multi-select.css ">
        <script>
            $(document).ready(function() {
                $('.multiselect').multiSelect({
                    'selectableHeader': '<div class="has-text-centered has-text-weight-bold">{{ __('Selectable') }}</div>',
                    'selectionHeader': '<div class="has-text-centered has-text-weight-bold">{{ __('Selected') }}</div>'
                });
            });
        </script>
        <div class="title">{{ __('Create schedule') }}</div>

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    {{ __('Create schedule') }}
                </p>
            </header>

            <div class="card-content">
                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf
                    <div class="columns">
                        <div class="column is-4">
                            <div class="field">
                                <label class="label">Name</label>
                                <input required class="input" type="text" name="name" />
                            </div>
                            <div class="field">
                                <label class="label">{{ __('Assigned template') }}</label>
                                <div class="select is-fullwidth">
                                    <select required name="presentation_id" id="">
                                        <option value="">{{ __('Select a template') }}...</option>
                                        @foreach ($presentations as $presentation)
                                            <option value="{{ $presentation->id }}">{{ $presentation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">{{ __('Start Date') }}</label>
                                <input required data-date-format="dd.MM.yyyy" type="date" name="start_date" value="{{ now() }}">
                            </div>
                            <div class="field">
                                <label class="label">{{ __('End Date') }}</label>
                                <input required data-date-format="dd.MM.yyyy" type="date" name="end_date" value="{{ now() }}">
                            </div>
                        </div>
                        <div class="column is-4">
                            <label class="label">{{ __('Devices') }}</label>
                            <select multiple="multiple" name="devices[]" class="multiselect">
                                @foreach ($devices as $device)
                                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="column is-4">
                            <label class="label">{{ __('Groups') }}</label>
                            <select multiple="multiple" name="groups[]" class="multiselect">
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button class="button is-success">{{ __('Create & enable') }}</button>
                    <button class="button is-primary">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    @endcan
    @cannot('create schedules')
        @include('unauthorized')
    @endcannot
@endsection