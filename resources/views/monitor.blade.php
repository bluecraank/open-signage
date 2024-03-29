<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $device->name }} | Monitor: {{ $device->getPresentation()?->name }} | {{ count($slides) }} Slides</title>
    <script src="/data/js/jquery-3.7.1.min.js"></script>
    @vite(['resources/css/monitor.css'])
</head>

<body>
    <div class="no-connection">
        {{ __('No connection to server') }}
    </div>

    @if (\App\Models\Setting::get('LOADING_BACKGROUND_TYPE') == 'color')
        <style>
            .loading {
                background-color: {{ \App\Models\Setting::get('LOADING_BACKGROUND_COLOR') }};
            }
        </style>
    @else
        <style>
            .loading {
                background-color: white;
                background-size: cover;
                background-repeat: no-repeat;
                background-image: url('{{ \App\Models\Setting::get('LOADING_BACKGROUND_IMAGE') }}');
            }
        </style>
    @endif

    <div class="loading">
        <div class="container">
            <div>
                <div class="lds-ripple">
                    <div></div>
                    <div></div>
                </div>

                <div class="text">
                    {{ \App\Models\Setting::getLoadingText($device) }}
                </div>
            </div>
        </div>
    </div>

    <div class="slides">
        @php $firstSlide = true @endphp
        @foreach ($slides as $slide)
            @if ($slide['type'] == 'image')
                <div class="slide"
                    style="
                    opacity:    @if ($firstSlide) @php $firstSlide = false; @endphp
                                    1
                                @else
                                    0 @endif;
                    background-image: url('{{ $slide['url'] }}')">
                </div>
            @elseif ($slide['type'] == 'video')
                <div class="slide"
                    style="
                    opacity:    @if ($firstSlide) @php $firstSlide = false; @endphp
                                    1
                                @else
                                    0 @endif;">
                    <video autoplay>
                        <source src="{{ $slide['url'] }}" type="video/mp4">
                    </video>
                </div>
            @endif
        @endforeach
    </div>

    <script>
        var currentSlide = 0;

        // Refresh page to prevent memory leaks
        function reload_page() {
            location.reload();
        }

        // Launch full screen
        function launchFullscreen(element) {
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
        }

        launchFullscreen(document.documentElement);

        // Check for updates
        var last_update = {{ $last_update }};
        var startup_timestamp = {{ time() }};

        function giveServerUpdate() {
            $.ajax({
                url: '/api/devices/monitor/update',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    last_update: last_update,
                    startup_timestamp: startup_timestamp,
                    presentation_id: {{ $device->getPresentationId() }},
                    currentSlide: currentSlide,
                    secret: '{{ $device->secret }}'
                },
                success: function(data) {
                    $(".no-connection").fadeOut(200);
                    data = JSON.parse(data);
                    if (data.last_update != last_update) {
                        console.log("[MISMANAGER] A new update is available, reloading page");
                        location.reload();
                    }

                    if (data.presentation_id != {{ $device->getPresentationId() }}) {
                        console.log("[MISMANAGER] A new presentation was assigned, reloading page");
                        location.reload();
                    }

                    if (data.force_reload) {
                        console.log("[MISMANAGER] A force reload was requested, reloading page");
                        location.reload();
                    }
                },
                error: function(data) {
                    console.log("[MISMANAGER] An error occured while checking for updates");
                    $(".no-connection").fadeIn(200);
                },
                timeout: 5000
            });
        }

        $(document).ready(function() {
                // Set trigger for page reload
                setTimeout(function() {
                    $.ajax({
                    url: '/api/devices/monitor/update',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        last_update: last_update,
                        startup_timestamp: startup_timestamp,
                        presentation_id: {{ $device->getPresentationId() }},
                        currentSlide: currentSlide,
                        secret: '{{ $device->secret }}'
                    },
                    success: function(data) {
                        reload_page();
                    },
                    error: function(data) {
                        console.log("[MISMANAGER] An error occured while checking for updates");
                    },
                    timeout: 5000
                });
            }, {{ \App\Models\Setting::get('MONITOR_REFRESH_TIME_SECONDS') }} * 1000)

            // Hide cursor
            $("body").css("cursor", "none");

            // Sliding effect

            let interval = {{ \App\Models\Setting::get('INTERVAL_NEXT_SLIDE_MS') }};

            var slides = document.querySelectorAll('.slide');

            var slideInterval = setInterval(nextSlide, interval);

            setTimeout(() => {
                $(".loading").fadeOut(1000);
            }, 6000);

            function nextSlide() {
                if ($('.loading')) {
                    $(".loading").remove();
                }

                // Only go to next slide if video is finished
                if (slides[currentSlide].querySelector('video') && !slides[currentSlide].querySelector('video')
                    .ended) {
                    console.log("Slide has video, but video is not finished")
                    return;
                }

                // Prevent slide change if there is only one slide
                if (slides.length == 1) {

                    // If video is ended, restart
                    if (slides[currentSlide].querySelector('video') && slides[currentSlide].querySelector('video')
                        .ended) {
                        console.log("[MISMANAGER] Video is ended, restarting");
                        let video = slides[currentSlide].querySelector('video');
                        video.pause();
                        video.currentTime = 0;
                        video.play();
                    }

                    console.log("[MISMANAGER] There is only one slide, not changing")
                    giveServerUpdate();
                    return;
                }


                console.log('[MISMANAGER] Triggering next slide');

                $(slides[currentSlide]).animate({
                    'opacity': '0'
                }, {{ \App\Models\Setting::get('SLIDE_OUT_TIME_MS') }});

                if (slides[currentSlide].querySelector('video')) {
                    let video = slides[currentSlide].querySelector('video');
                    video.pause();
                    video.currentTime = 0;
                }

                currentSlide = (currentSlide + 1) % slides.length;

                // Update informations
                giveServerUpdate();

                $(slides[currentSlide]).animate({
                    'opacity': '1'
                }, {{ \App\Models\Setting::get('SLIDE_IN_TIME_MS') }});

                // Play video
                if (slides[currentSlide].querySelector('video')) {
                    let video = slides[currentSlide].querySelector('video');
                    video.play();
                }
            }
        });
    </script>

</body>

</html>
