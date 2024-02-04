@extends('layouts.app')

@section('content')
    @can('create schedules')
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
        <h3 class="mb-3">{{ __('Schedules') }}</h3>

        <div class="card">
            <h5 class="card-header">{{ __('Create schedule') }}</h5>
            <div class="card-body">
                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                        <input required type="text" class="form-control" name="name" placeholder="Name" id="name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Template') }}<span class="text-danger">*</span></label>
                        <select required class="form-select" name="presentation_id">
                            <option value="">{{ __('Select a template') }}...</option>
                            @foreach ($presentations as $presentation)
                                <option value="{{ $presentation->id }}" @if (!$presentation->processed) disabled @endif>
                                    {{ $presentation->name }} @if (!$presentation->processed)
                                        ({{ __('In process') }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">{{ __('Start date') }}<span
                                        class="text-danger">*</span></label>
                                <input required type="datetime-local" class="form-control" name="start_date" id="start_date"
                                    value="{{ now()->format('Y-m-d H:m') }}">
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">{{ __('End date') }}<span
                                        class="text-danger">*</span></label>
                                    <input required type="datetime-local" class="form-control" name="end_date" id="end_date"
                                    value="{{ now()->addDays(1)->format('Y-m-d') }}T00:00">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-5">
                            <div class="d-flex justify-content-center align-items-center">
                                <div>
                                    <label class="form-label">{{ __('Devices') }}<span class="text-danger">*</span></label>
                                    <select multiple="multiple" name="devices[]" class="multiselect">
                                        @foreach ($devices as $device)
                                            <option value="{{ $device->id }}">{{ $device->description }}
                                                ({{ $device->name }})
                                            </option>
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
                                    <label class="form-label">{{ __('Groups') }}<span class="text-danger">*</span></label>
                                    <select multiple="multiple" name="groups[]" class="multiselect">
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <button type="submit" name="submit_with_enable" class="btn btn-primary">{{ __('Save & enable') }}</button>
                    <button type="reset" class="btn btn-danger">{{ __('Reset') }}</button>
                </form>
            </div>
        </div>
    @endcan
    @cannot('create schedules')
        @include('unauthorized')
    @endcannot
@endsection
