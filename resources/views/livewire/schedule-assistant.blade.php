<div class="card">
    <h5 class="card-header">
        {{ __('Schedule assistant') }}
    </h5>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Wer kommt zu Besuch? / Welcher Anlass?</label>
                    <br>
                    <small class="form-text text-muted">
                        {{ __('Please enter the name of the company or person who will visit you or the reason for the visit.') }}
                    </small>
                    <input type="text" class="form-control form-control-lg mt-2" id="name"
                        wire:model.live.debounce.500ms="name" placeholder="Name der Firma / Personen">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if ($showDateFields)
                    <label for="name" class="mt-3">Wann kommt der Besuch?</label>
                    <br>
                    <small class="form-text text-muted">
                        {{ __('Please enter the date and time when the visit will take place.') }}
                    </small>

                    @foreach ($days as $key => $day)
                        <div class="row mt-2">
                            <h6>
                                Zeitraum {{ $key + 1 }}
                            </h6>
                            <div class="col-6">
                                <div
                                    class="form-group
                            @if ($errors->has('startDate') || $errors->has('startDate')) has-error @endif">
                                    <label for="startDate">{{ __('Start date') }}</label>
                                    <input type="datetime-local" value="{{ $day['startDate'] }}"
                                        class="form-control form-control-lg" id="startDate"
                                        wire:model.live.debounce.500ms="days.{{ $key }}.startDate">
                                    @error('startDate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-5">
                                <div
                                    class="form-group
                            @if ($errors->has('endDate') || $errors->has('endDate')) has-error @endif">
                                    <label for="endDate">{{ __('End date') }}</label>
                                    <input type="datetime-local" class="form-control form-control-lg" id="endDate"
                                        wire:model.live.debounce.500ms="days.{{ $key }}.endDate">
                                    @error('endDate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-1 vertical-middle">
                                <label for="">&nbsp;</label>
                                <button class="d-block btn btn-danger btn-lg" wire:click="removeDay({{ $key }})">
                                    <i class="bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <div class="float-end">
                        @if ($showDateOptions)
                            <div class="form-check mt-2 d-inline-block">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Pro Tag wird eine bestimmte Präsentation benötigt
                                </label>
                            </div>
                        @endif
                        <button class="btn btn-primary btn-sm mt-3 d-inline-block" wire:click="addDay">
                            <i class="bi-plus"></i> Zeitraum hinzufügen
                        </button>
                    </div>
                @endif

                @if ($showDateOptions)
                    <div class="form-group mt-3">
                        <label for="name">Welche Präsentation wird benötigt?</label>
                        <br>
                        <small class="form-text text-muted">
                            {{ __('Please select the presentation that is required for the visit.') }}
                        </small>
                        <select class="form-select form-select-lg mt-2" wire:model="presentationId">
                            <option value="">{{ __('Select a presentation') }}</option>
                            @foreach ($presentations as $presentation)
                                <option value="{{ $presentation->id }}">{{ $presentation->name }}</option>
                            @endforeach
                        </select>
                        @error('presentationId')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                @endif
            </div>
        </div>
    </div>
</div>
