@extends('layouts.app')

@section('content')
    @can('update schedules')
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

            function validate() {
                var start_date = document.getElementsByName('start_date')[0].value;
                var end_date = document.getElementsByName('end_date')[0].value;

                // Check if dates are correct
                if (start_date >= end_date) {
                    alert('{{ __('Start date must be before end date') }}');
                    return false;
                }

                // Check if at least one device or group is selected
                var devices = document.getElementsByName('devices[]')[0].selectedOptions.length;
                var groups = document.getElementsByName('groups[]')[0].selectedOptions.length;

                if (devices == 0 && groups == 0) {
                    alert('{{ __('You must select at least one device or group') }}');
                    return false;
                }

                return true;
            }
        </script>
        <div class="title">{{ __('Update schedule') }}</div>

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    {{ __('Update schedule') }}
                </p>
            </header>

            <div class="card-content">
                <form action="{{ route('schedules.update', ['id' => $schedule->id]) }}" method="POST" onsubmit="return validate()">
                    @csrf
                    @method('PUT')
                    <div class="columns">
                        <div class="column is-4">
                            <div class="field">
                                <label class="label">Name</label>
                                <input required class="input" type="text" name="name" value="{{ $schedule->name }}" />
                            </div>
                            <div class="field">
                                <label class="label">{{ __('Assigned template') }}</label>
                                <div class="select is-fullwidth">
                                    <select required name="presentation_id" id="">
                                        <option value="">{{ __('Select a template') }}...</option>
                                        @foreach ($presentations as $presentation)
                                            <option @if($schedule->presentation_id == $presentation->id) selected @endif value="{{ $presentation->id }}">{{ $presentation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">{{ __('Start Date') }}</label>
                                <input required data-date-format="dd.MM.yyyy" data-time-format="HH:mm" type="date" name="start_date" data-start-date="{{ Carbon::parse($schedule->startDate())->toDateString() }}" data-start-time="{{ Carbon::parse($schedule->startDate())->format('H:i') }}">
                            </div>
                            <div class="field">
                                <label class="label">{{ __('End Date') }}</label>
                                <input required data-date-format="dd.MM.yyyy" data-time-format="hh:mm" type="date" name="end_date" data-start-date="{{ Carbon::parse($schedule->endDate())->toDateString() }}" data-start-time="{{ Carbon::parse($schedule->endDate())->format('H:i') }}">
                            </div>
                        </div>
                        <div class="column is-4">
                            <label class="label">{{ __('Devices') }}</label>
                            <select multiple="multiple" name="devices[]" class="multiselect">
                                @foreach ($devices as $device)
                                    <option @if(in_array($device->id, $schedule->devices)) selected @endif value="{{ $device->id }}">{{ $device->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="column is-4">
                            <label class="label">{{ __('Groups') }}</label>
                            <select multiple="multiple" name="groups[]" class="multiselect">
                                @foreach ($groups as $group)
                                    <option @if(in_array($group->id, $schedule->groups)) selected @endif value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button class="button is-success">{{ __('Update & enable') }}</button>
                    <button class="button is-primary">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    @endcan
    @cannot('update schedules')
        @include('unauthorized')
    @endcannot
@endsection
