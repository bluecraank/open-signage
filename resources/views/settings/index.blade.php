@extends('layouts.app')

@section('content')
    @can('manage settings')
        <div class="title">{{ __('Global') }} {{ __('Monitorsettings') }}</div>

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    {{ __('Configure monitor preferences') }}
                </p>
            </header>

            <div class="card-content">
                <form action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="columns">
                        <div class="column is-4 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">MONITOR_REFRESH_TIME_SECONDS</label>
                                <input class="input is-fullwidth" type="text" name="MONITOR_REFRESH_TIME_SECONDS"
                                    value="{{ $settings['MONITOR_REFRESH_TIME_SECONDS']['value'] }}" />
                            </div>
                        </div>

                        <div class="column is-8 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">&nbsp;</label>
                                <p>Every x seconds forced reload of page to prevent memory leaks. Value in seconds | Default: 43200 (12h)</p>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column is-4 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">MONITOR_CHECK_UPDATE_TIME_SECONDS</label>
                                <input class="input is-fullwidth" type="text" name="MONITOR_CHECK_UPDATE_TIME_SECONDS"
                                    value="{{ $settings['MONITOR_CHECK_UPDATE_TIME_SECONDS']['value'] }}" />
                            </div>
                        </div>
                        <div class="column is-8 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">&nbsp;</label>
                                <p>Interval request to server to check for updates. Value in seconds | Default: 30</p>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column is-4 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">INTERVAL_NEXT_SLIDE_MS</label>
                                <input class="input is-fullwidth" type="text" name="INTERVAL_NEXT_SLIDE_MS"
                                    value="{{ $settings['INTERVAL_NEXT_SLIDE_MS']['value'] }}" />
                            </div>
                        </div>
                        <div class="column is-8 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">&nbsp;</label>
                                <p>Time between each slide. Value in ms | Default: 20000</p>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column is-4 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">SLIDE_IN_TIME_MS</label>
                                <input class="input is-fullwidth" type="text" name="SLIDE_IN_TIME_MS"
                                    value="{{ $settings['SLIDE_IN_TIME_MS']['value'] }}" />
                            </div>
                        </div>
                        <div class="column is-8 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">&nbsp;</label>
                                <p>Animation time to show next slide. Value in ms | Default: 1600</p>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column is-4 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">SLIDE_OUT_TIME_MS</label>
                                <input class="input is-fullwidth" type="text" name="SLIDE_OUT_TIME_MS"
                                    value="{{ $settings['SLIDE_OUT_TIME_MS']['value'] }}" />
                            </div>
                        </div>
                        <div class="column is-8 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">&nbsp;</label>
                                <p>Animation time to hide current slide. Value in ms | Default: 1100</p>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column is-4 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">LOADING_BACKGROUND_TYPE</label>
                                <input class="input is-fullwidth" type="text" name="LOADING_BACKGROUND_TYPE"
                                    value="{{ $settings['LOADING_BACKGROUND_TYPE']['value'] }}" />
                            </div>
                        </div>
                        <div class="column is-8 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">&nbsp;</label>
                                <p>Select "image" or "color" to choose which type of loading screen should appear on monitor | Default: image</p>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column is-4 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">LOADING_BACKGROUND_TEXT</label>
                                <input class="input is-fullwidth" type="text" name="LOADING_BACKGROUND_TEXT"
                                    value="{{ $settings['LOADING_BACKGROUND_TEXT']['value'] }}" />
                            </div>
                        </div>
                        <div class="column is-8 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">&nbsp;</label>
                                <p>Additional text on loading screen | Default: empty</p>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column is-4 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">LOADING_BACKGROUND_COLOR</label>
                                <input class="input is-fullwidth" type="text" name="LOADING_BACKGROUND_COLOR"
                                    value="{{ $settings['LOADING_BACKGROUND_COLOR']['value'] }}" />
                            </div>
                        </div>
                        <div class="column is-8 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">&nbsp;</label>
                                <p>Select HEX of background color of loading screen. This setting only applies if TYPE is "color" | Default: #000000</p>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column is-4 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">LOADING_BACKGROUND_IMAGE</label>
                                <input class="input is-fullwidth" type="text" name="LOADING_BACKGROUND_IMAGE"
                                    value="{{ $settings['LOADING_BACKGROUND_IMAGE']['value'] }}" />
                            </div>
                        </div>
                        <div class="column is-8 is-flex is-align-content-center is-flex-wrap-wrap">
                            <div class="field is-fullwidth">
                                <label class="label">&nbsp;</label>
                                <p>URL of background image of loading screen. This setting only applies if TYPE is "image" | Default: https://picsum.photos/1920/1080</p>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column is-4 is-flex is-align-content-center is-flex-wrap-wrap">
                            <button type="submit" class="button is-fullwidth is-primary">{{ __('Save') }}</button>
                        </div>
                        <div class="column is-8 is-flex is-align-content-center is-flex-wrap-wrap">
                            <span><i class="mdi mdi-information-slab-circle-outline has-text-danger mr-1"></i>{{ __('Saving the settings forces a reload of all active monitors') }}</span>
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
