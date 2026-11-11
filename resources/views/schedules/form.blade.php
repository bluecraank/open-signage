{{-- Shared form for creating and editing a schedule, $schedule is null when creating --}}
@include('partials.multiselect')
<script>
    function validate() {
        var start_date = document.getElementsByName('start_date')[0].value;
        var end_date = document.getElementsByName('end_date')[0].value;

        // Check if dates are correct
        if (start_date >= end_date) {
            alert(@js(__('Start date must be before end date')));
            return false;
        }

        // Check if at least one device or group is selected
        var devices = document.getElementsByName('devices[]')[0].selectedOptions.length;
        var groups = document.getElementsByName('groups[]')[0].selectedOptions.length;

        if (devices == 0 && groups == 0) {
            alert(@js(__('You must select at least one device or group')));
            return false;
        }

        return true;
    }
</script>

<form action="{{ $schedule ? route('schedules.update', ['id' => $schedule->id]) : route('schedules.store') }}"
    method="POST" onsubmit="return validate()">
    @csrf
    @if ($schedule)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label for="name" class="form-label">{{ __('Description of schedule') }}<span class="text-danger">*</span></label>
            <input required type="text" class="form-control" name="name" id="name"
                placeholder="{{ __('Description of schedule') }}" value="{{ old('name', $schedule?->name) }}">
        </div>

        <div class="col-md-6">
            <label class="form-label" for="presentation_id">{{ __('Template') }}<span class="text-danger">*</span></label>
            <select required class="form-select" name="presentation_id" id="presentation_id">
                <option value="">{{ __('Select a template') }}...</option>
                @foreach ($presentations as $presentation)
                    <option value="{{ $presentation->id }}" @selected(old('presentation_id', $schedule?->presentation_id) == $presentation->id)
                        @disabled(!$presentation->processed)>
                        {{ $presentation->name }} @if (!$presentation->processed) ({{ __('In process') }}) @endif
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label for="start_date" class="form-label">{{ __('Start date') }}<span class="text-danger">*</span></label>
            <input required type="datetime-local" class="form-control" name="start_date" id="start_date"
                value="{{ $schedule ? $schedule->start_time?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:00') }}">
        </div>

        <div class="col-md-6">
            <label for="end_date" class="form-label">{{ __('End date') }}<span class="text-danger">*</span></label>
            <input required type="datetime-local" class="form-control" name="end_date" id="end_date"
                value="{{ $schedule ? $schedule->end_time?->format('Y-m-d\TH:i') : now()->addHours(1)->format('Y-m-d\TH:00') }}">
        </div>

        <div class="col-lg-6">
            <label class="form-label">{{ __('Devices') }}</label>
            <select multiple="multiple" name="devices[]" class="multiselect">
                @foreach ($devices as $device)
                    <option @selected(in_array($device->id, $schedule?->devices ?? [])) value="{{ $device->id }}">
                        {{ $device->description }} ({{ $device->name }})</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-6">
            <label class="form-label">{{ __('Groups') }}</label>
            <select multiple="multiple" name="groups[]" class="multiselect">
                @foreach ($groups as $group)
                    <option @selected(in_array($group->id, $schedule?->groups ?? [])) value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
            <div class="form-text">{{ __('Select at least one device or group.') }}</div>
        </div>

        <div class="col-12">
            <div class="form-check form-switch">
                <input type="checkbox" role="switch" class="form-check-input" name="del_pres_after_schedule_ends"
                    id="delete-pres-after-schedule" @checked($schedule?->delete_presentation)>
                <label class="form-check-label" for="delete-pres-after-schedule">{{ __('Delete presentation after schedule ends') }}</label>
                <div class="form-text">{{ __('The template is kept if a device, a group or another schedule still uses it.') }}</div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ $schedule ? __('Save') : __('Save & enable') }}</button>
        <a href="{{ route('schedules.index') }}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
    </div>
</form>
