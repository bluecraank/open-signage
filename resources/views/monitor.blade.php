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
                    {{-- Browsers block autoplay with sound, a muted video starts without user interaction --}}
                    {{-- In sync mode the script starts each video at the right moment --}}
                    <video @if (!$sync) autoplay @endif muted playsinline preload="auto"
                        @if (count($slides) === 1) loop @endif>
                        <source src="{{ $slide['url'] }}" type="video/mp4">
                    </video>
                </div>
            @endif
        @endforeach
    </div>

    <script>
        var currentSlide = 0;

        // Synchronized groups: every monitor derives the current slide from the server clock
        var SYNC = {{ $sync ? 'true' : 'false' }};
        var SYNC_ANCHOR = {{ $sync_anchor }};
        var serverOffset = {{ $server_time }} - Date.now();
        var offsetSamples = [];

        function serverNow() {
            return Date.now() + serverOffset;
        }

        // The sample with the shortest round trip gives the most exact offset
        function addOffsetSample(serverTime, sentAt) {
            let receivedAt = Date.now();
            let rtt = receivedAt - sentAt;
            offsetSamples.push({
                rtt: rtt,
                offset: serverTime + rtt / 2 - receivedAt
            });
            if (offsetSamples.length > 8) {
                offsetSamples.shift();
            }
            serverOffset = offsetSamples.reduce((a, b) => a.rtt <= b.rtt ? a : b).offset;
        }

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
            let sentAt = Date.now();
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

                    if (typeof data.synchronized !== 'undefined' && data.synchronized !== SYNC) {
                        console.log("[MISMANAGER] Group synchronization was changed, reloading page");
                        location.reload();
                    }

                    if (data.server_time) {
                        addOffsetSample(data.server_time, sentAt);
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

            if (SYNC) {
                // Measure the clock offset right away instead of waiting for the first regular report
                giveServerUpdate();
                startSync();
            } else {
                var slideInterval = setInterval(nextSlide, interval);
            }

            // Report independently of slide changes, a long video or slide interval would look like an offline monitor
            setInterval(giveServerUpdate, {{ \App\Models\Setting::get('MONITOR_CHECK_UPDATE_TIME_SECONDS') }} * 1000);

            // Image slides last one interval, video slides last as long as the video. Every monitor of the
            // group loads the same files, so all of them compute the same timeline.
            function startSync() {
                let durations = [];
                let pending = 0;

                slides.forEach(function(slide, i) {
                    let video = slide.querySelector('video');
                    if (!video) {
                        durations[i] = interval;
                        return;
                    }

                    video.pause();
                    pending++;
                    let done = false;
                    let finish = function() {
                        if (done) return;
                        done = true;
                        durations[i] = isFinite(video.duration) && video.duration > 0 ?
                            Math.round(video.duration * 1000) : interval;
                        if (--pending === 0) runSync(durations);
                    };

                    if (video.readyState >= 1) {
                        finish();
                    } else {
                        video.addEventListener('loadedmetadata', finish);
                        // A broken video must not stop the slideshow
                        setTimeout(finish, 15000);
                    }
                });

                if (pending === 0) runSync(durations);
            }

            function runSync(durations) {
                let total = durations.reduce((a, b) => a + b, 0);
                console.log("[MISMANAGER] Synchronized mode, cycle length " + total + " ms");

                function tick() {
                    let position = ((serverNow() - SYNC_ANCHOR) % total + total) % total;
                    let index = 0;
                    while (index < durations.length - 1 && position >= durations[index]) {
                        position -= durations[index];
                        index++;
                    }

                    if (index !== currentSlide) {
                        showSlide(index);
                    }

                    syncVideo(slides[currentSlide].querySelector('video'), position / 1000);
                }

                tick();
                setInterval(tick, 100);
            }

            function showSlide(index) {
                if ($('.loading')) {
                    $(".loading").remove();
                }

                $(slides[currentSlide]).stop().animate({
                    'opacity': '0'
                }, {{ \App\Models\Setting::get('SLIDE_OUT_TIME_MS') }});

                let oldVideo = slides[currentSlide].querySelector('video');
                if (oldVideo) {
                    oldVideo.pause();
                    oldVideo.currentTime = 0;
                }

                currentSlide = index;
                giveServerUpdate();

                $(slides[currentSlide]).stop().animate({
                    'opacity': '1'
                }, {{ \App\Models\Setting::get('SLIDE_IN_TIME_MS') }});
            }

            // Keep the video at the position the server clock dictates
            function syncVideo(video, target) {
                // play() on an ended video would restart it, the next tick switches to the next slide anyway
                if (!video || video.ended) return;

                if (video.paused) {
                    video.play().catch(() => {});
                }

                if (video.seeking) return;

                let drift = video.currentTime - target;
                if (Math.abs(drift) > 0.5) {
                    video.currentTime = target;
                    video.playbackRate = 1;
                } else if (Math.abs(drift) > 0.05) {
                    // Small drift is corrected by playing slightly faster or slower, seeking would stutter
                    video.playbackRate = Math.min(1.05, Math.max(0.95, 1 - drift));
                } else {
                    video.playbackRate = 1;
                }
            }

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
