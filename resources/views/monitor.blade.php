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
                    {{ \App\Models\Setting::get('LOADING_BACKGROUND_TEXT') }}
                </div>
            </div>
        </div>
    </div>

    <div class="slides">
        @php $firstSlide = true @endphp
        @foreach ($slides as $slide)
            <div class="slide"
                style="
                    opacity:    @if ($firstSlide) @php $firstSlide = false; @endphp
                                    1
                                @else
                                    0 @endif;
                    background-image: url('{{ $slide }}')">
            </div>
        @endforeach
    </div>

    <script>
        // Refresh page to prevent memory leaks
        function reload_page() {
            location.reload();
        }

        var currentSlide = 0;

        $(document).ready(function() {
            // Set trigger for page reload
            setTimeout(function() {
                reload_page()
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

                // Prevent slide change if there is only one slide
                if (slides.length == 1) return;

                console.log('[MISMANAGER] Triggering next slide');

                if (document.hasFocus()) {
                    $(slides[currentSlide]).animate({
                        'opacity': '0'
                    }, {{ \App\Models\Setting::get('SLIDE_OUT_TIME_MS') }});
                    currentSlide = (currentSlide + 1) % slides.length;
                    $(slides[currentSlide]).animate({
                        'opacity': '1'
                    }, {{ \App\Models\Setting::get('SLIDE_IN_TIME_MS') }});
                }
            }
        });

        // Check for updates
        let last_update = {{ $last_update }};
        let startup_timestamp = {{ time() }};

        let checkUpdate = setInterval(function() {
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
                },
                timeout: 5000
            });
        }, {{ \App\Models\Setting::get('MONITOR_CHECK_UPDATE_TIME_SECONDS') }} * 1000);
    </script>

</body>

</html>
