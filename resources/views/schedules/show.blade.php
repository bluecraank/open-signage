@extends('layouts.app')

@section('content')
    @can('update schedules')
        <script src="/data/js/jquery-3.7.1.min.js"></script>
        <script src="/data/js/jquery.multi-select.js"></script>
        <link rel="stylesheet" href="/data/css/multi-select.css ">
        <script>
            $(document).ready(function() {
                $('.multiselect').multiSelect({
                    'selectableHeader': '<div class="text-center has-text-weight-bold">{{ __('Selectable') }}</div>',
                    'selectionHeader': '<div class="text-center has-text-weight-bold">{{ __('Selected') }}</div>'
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
        <h3 class="mb-3">{{ __('Schedule') }}: {{ $schedule->name }}</h3>

        <div class="card">
            <h5 class="card-header">
                {{ __('Update schedule') }}
            </h5>

            <div class="card-body">
                <form action="{{ route('schedules.update', ['id' => $schedule->id]) }}" method="POST"
                    onsubmit="return validate()">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Name<span class="text-danger">*</span></label>
                        <input required class="form-control" type="text" name="name" value="{{ $schedule->name }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Assigned template') }}<span class="text-danger">*</span></label>
                        <select class="form-select" required name="presentation_id" id="">
                            <option value="">{{ __('Select a template') }}...</option>
                            @foreach ($presentations as $presentation)
                                <option @if ($schedule->presentation_id == $presentation->id) selected @endif value="{{ $presentation->id }}">
                                    {{ $presentation->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">{{ __('Start Date') }}<span class="text-danger">*</span></label>
                            <input required type="datetime-local" class="form-control" name="start_date" value="{{ $schedule->start_time }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">{{ __('End Date') }}<span class="text-danger">*</span></label>
                            <input required type="datetime-local" class="form-control" name="end_date" value="{{ $schedule->end_time }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <div class="d-flex justify-content-center align-items-center">
                                <div>
                                    <label class="form-label">{{ __('Devices') }}</label>
                                    <select multiple="multiple" name="devices[]" class="multiselect">
                                        @foreach ($devices as $device)
                                            <option @if (in_array($device->id, $schedule->devices)) selected @endif value="{{ $device->id }}">
                                                {{ $device->description }} ({{ $device->name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-2 d-flex justify-content-center">
                            <div class="d-flex align-items-center">
                                <h5 class="w-full text-center">
                                    {{ __('and/or') }}
                                </h5>
                            </div>
                        </div>

                        <div class="col-5">
                            <div class="d-flex justify-content-center align-items-center">
                                <div>
                                    <label class="form-label">{{ __('Groups') }}</label>
                                    <select multiple="multiple" name="groups[]" class="multiselect">
                                        @foreach ($groups as $group)
                                            <option @if (in_array($group->id, $schedule->groups)) selected @endif value="{{ $group->id }}">
                                                {{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </form>

                @can('delete schedules')
                    <form action="{{ route('schedules.destroy', ['id' => $schedule->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button style="margin-top:-35px" type="submit"
                            class="btn btn-danger float-end">{{ __('Delete') }}</button>
                    </form>
                @endcan
            </div>
        </div>
    @endcan
    @cannot('update schedules')
        @include('unauthorized')
    @endcannot
@endsection
