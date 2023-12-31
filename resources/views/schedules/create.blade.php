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
        <div class="title">{{ __('Create schedule') }}</div>

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    {{ __('Create schedule') }}
                </p>
            </header>

            <div class="card-content">
                <form action="{{ route('schedules.store') }}" method="POST" onsubmit="return validate()">
                    @csrf
                    <div class="columns">
                        <div class="column is-4">
                            <div class="field">
                                <label class="label">Name<span class="has-text-danger">*</span></label>
                                <input required class="input" type="text" name="name" />
                            </div>
                            <div class="field">
                                <label class="label">{{ __('Assigned template') }}<span class="has-text-danger">*</span></label>
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
                                <label class="label">{{ __('Start Date') }}<span class="has-text-danger">*</span></label>
                                <input required data-date-format="dd.MM.yyyy" type="date" name="start_date" value="{{ now() }}">
                            </div>
                            <div class="field">
                                <label class="label">{{ __('End Date') }}<span class="has-text-danger">*</span></label>
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

                    <button type="submit" name="submit_with_enable" class="button is-success">{{ __('Save & enable') }}</button>
                    <button type="submit" name="submit_without_enable" class="button is-primary">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    @endcan
    @cannot('create schedules')
        @include('unauthorized')
    @endcannot
@endsection
