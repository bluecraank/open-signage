@extends('layouts.app')

@section('content')
    @can('manage settings')
        <h3 class="mb-3">{{ __('Global') }} {{ __('Monitorsettings') }}</h3>

        <div class="card">
            <h5 class="card-header">
                {{ __('Configure monitor preferences') }}
            </h5>
            <div class="card-body">
                <form action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mt-4">
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">MONITOR_REFRESH_TIME_SECONDS</label>
                                <input class="form-control" type="text" name="MONITOR_REFRESH_TIME_SECONDS"
                                    value="{{ $settings['MONITOR_REFRESH_TIME_SECONDS']['value'] }}" />
                            </div>
                        </div>

                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">&nbsp;</label>
                                <p>Every x seconds forced reload of page to prevent memory leaks. Value in seconds | Default: 43200 (12h)</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">INTERVAL_NEXT_SLIDE_MS</label>
                                <input class="form-control" type="text" name="INTERVAL_NEXT_SLIDE_MS"
                                    value="{{ $settings['INTERVAL_NEXT_SLIDE_MS']['value'] }}" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">&nbsp;</label>
                                <p>Time between each slide. Value in ms | Default: 20000</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">SLIDE_IN_TIME_MS</label>
                                <input class="form-control" type="text" name="SLIDE_IN_TIME_MS"
                                    value="{{ $settings['SLIDE_IN_TIME_MS']['value'] }}" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">&nbsp;</label>
                                <p>Animation time to show next slide. Value in ms | Default: 1600</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">SLIDE_OUT_TIME_MS</label>
                                <input class="form-control" type="text" name="SLIDE_OUT_TIME_MS"
                                    value="{{ $settings['SLIDE_OUT_TIME_MS']['value'] }}" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">&nbsp;</label>
                                <p>Animation time to hide current slide. Value in ms | Default: 1100</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">LOADING_BACKGROUND_TYPE</label>
                                <input class="form-control" type="text" name="LOADING_BACKGROUND_TYPE"
                                    value="{{ $settings['LOADING_BACKGROUND_TYPE']['value'] }}" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">&nbsp;</label>
                                <p>Select "image" or "color" to choose which type of loading screen should appear on monitor | Default: image</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">LOADING_BACKGROUND_TEXT</label>
                                <input class="form-control" type="text" name="LOADING_BACKGROUND_TEXT"
                                    value="{{ $settings['LOADING_BACKGROUND_TEXT']['value'] }}" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">&nbsp;</label>
                                <p>Additional text on loading screen | Default: empty</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">LOADING_BACKGROUND_COLOR</label>
                                <input class="form-control" type="text" name="LOADING_BACKGROUND_COLOR"
                                    value="{{ $settings['LOADING_BACKGROUND_COLOR']['value'] }}" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">&nbsp;</label>
                                <p>Select HEX of background color of loading screen. This setting only applies if TYPE is "color" | Default: #000000</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">LOADING_BACKGROUND_IMAGE</label>
                                <input class="form-control" type="text" name="LOADING_BACKGROUND_IMAGE"
                                    value="{{ $settings['LOADING_BACKGROUND_IMAGE']['value'] }}" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="field is-fullwidth">
                                <label class="form-label">&nbsp;</label>
                                <p>URL of background image of loading screen. This setting only applies if TYPE is "image" | Default: https://picsum.photos/1920/1080</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </div>
                        <div class="col">
                            <span class="text-warning"><i class="bi-info-circle"></i><span style="margin-left: 10px;">{{ __('Saving the settings forces a reload of all active monitors') }}</span></span>
                        </div>
                    </div>

                </form>
            </div>
          </div>
    @endcan
    @cannot('read presentations')
        @include('unauthorized')
    @endcannot
@endsection
