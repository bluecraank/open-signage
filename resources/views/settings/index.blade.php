@extends('layouts.app')

@section('title', __('Monitorsettings'))

@section('content')
    @php
        $value = fn ($key) => old($key, $settings[$key]['value'] ?? '');
    @endphp

    <x-page-header :title="__('Monitorsettings')" :subtitle="__('Global settings for all monitors')" />

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header"><span><i class="bi bi-play-btn me-1"></i>{{ __('Slideshow') }}</span></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="INTERVAL_NEXT_SLIDE_MS">{{ __('Time per slide') }}</label>
                            <div class="input-group">
                                <input class="form-control" type="number" min="1000" step="100" id="INTERVAL_NEXT_SLIDE_MS"
                                    name="INTERVAL_NEXT_SLIDE_MS" value="{{ $value('INTERVAL_NEXT_SLIDE_MS') }}" required>
                                <span class="input-group-text">ms</span>
                            </div>
                            <div class="form-text">{{ __('Default') }}: 20000. {{ __('Videos always play to the end.') }}</div>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label" for="SLIDE_IN_TIME_MS">{{ __('Fade in') }}</label>
                                <div class="input-group">
                                    <input class="form-control" type="number" min="100" step="100" id="SLIDE_IN_TIME_MS"
                                        name="SLIDE_IN_TIME_MS" value="{{ $value('SLIDE_IN_TIME_MS') }}" required>
                                    <span class="input-group-text">ms</span>
                                </div>
                                <div class="form-text">{{ __('Default') }}: 1100</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="SLIDE_OUT_TIME_MS">{{ __('Fade out') }}</label>
                                <div class="input-group">
                                    <input class="form-control" type="number" min="100" step="100" id="SLIDE_OUT_TIME_MS"
                                        name="SLIDE_OUT_TIME_MS" value="{{ $value('SLIDE_OUT_TIME_MS') }}" required>
                                    <span class="input-group-text">ms</span>
                                </div>
                                <div class="form-text">{{ __('Default') }}: 1600</div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label" for="MONITOR_REFRESH_TIME_SECONDS">{{ __('Full page reload') }}</label>
                            <div class="input-group">
                                <input class="form-control" type="number" min="1" id="MONITOR_REFRESH_TIME_SECONDS"
                                    name="MONITOR_REFRESH_TIME_SECONDS" value="{{ $value('MONITOR_REFRESH_TIME_SECONDS') }}" required>
                                <span class="input-group-text">s</span>
                            </div>
                            <div class="form-text">{{ __('Reloads the monitor page regularly to prevent memory leaks.') }} {{ __('Default') }}: 43200 (12h)</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header"><span><i class="bi bi-hourglass-split me-1"></i>{{ __('Loading screen') }}</span></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="LOADING_BACKGROUND_TYPE">{{ __('Background') }}</label>
                            <select class="form-select" id="LOADING_BACKGROUND_TYPE" name="LOADING_BACKGROUND_TYPE">
                                <option value="image" @selected($value('LOADING_BACKGROUND_TYPE') === 'image')>{{ __('Image') }}</option>
                                <option value="color" @selected($value('LOADING_BACKGROUND_TYPE') === 'color')>{{ __('Color') }}</option>
                            </select>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-sm-4">
                                <label class="form-label" for="LOADING_BACKGROUND_COLOR">{{ __('Color') }}</label>
                                <input class="form-control form-control-color w-100" type="color" id="LOADING_BACKGROUND_COLOR"
                                    name="LOADING_BACKGROUND_COLOR" value="{{ $value('LOADING_BACKGROUND_COLOR') ?: '#000000' }}">
                            </div>
                            <div class="col-sm-8">
                                <label class="form-label" for="LOADING_BACKGROUND_IMAGE">{{ __('Image URL') }}</label>
                                <input class="form-control" type="url" id="LOADING_BACKGROUND_IMAGE" name="LOADING_BACKGROUND_IMAGE"
                                    value="{{ $value('LOADING_BACKGROUND_IMAGE') }}" required>
                            </div>
                        </div>
                        <div>
                            <label class="form-label" for="LOADING_BACKGROUND_TEXT">{{ __('Text') }}</label>
                            <input class="form-control" type="text" id="LOADING_BACKGROUND_TEXT" name="LOADING_BACKGROUND_TEXT"
                                value="{{ $value('LOADING_BACKGROUND_TEXT') }}">
                            <div class="form-text">{{ __('Placeholders') }}: <code>{hostname}</code>, <code>{location}</code></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body d-flex flex-wrap align-items-center gap-3">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                <span class="text-warning-emphasis small"><i class="bi bi-info-circle me-1"></i>{{ __('Saving the settings forces a reload of all active monitors') }}</span>
            </div>
        </div>
    </form>
@endsection
