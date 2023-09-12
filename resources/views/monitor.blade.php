<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $device->name }} | Monitor: {{ $device->presentation?->name }} | {{ count($slides) }} Slides</title>
    <script src="/data/js/jquery-3.7.1.min.js"></script>
    @vite(['resources/css/monitor.css'])
</head>

<body>
    <div class="loading">
        <div class="container">
            <div class="lds-ripple">
                <div></div>
                <div></div>
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
            setTimeout(function() { reload_page() }, {{ config('app.monitor_refresh_time_seconds') }} * 1000)

            // Hide cursor
            $("body").css("cursor", "none");

            // Sliding effect

            let interval = {{ config('app.interval_next_slide_ms') }};

            var slides = document.querySelectorAll('.slide');

            var slideInterval = setInterval(nextSlide, interval);

            setTimeout(() => {
                $(".loading").hide();
            }, 6000);

            function nextSlide() {
                if ($('.loading')) {
                    $(".loading").remove();
                }

                // Prevent slide change if there is only one slide
                if (slides.length == 1) return;

                console.log('[MISMANAGER] Triggering next slide');

                if (document.hasFocus()) {
                    $(".loading").hide();
                    $(slides[currentSlide]).animate({
                        'opacity': '0'
                    }, {{ config('app.slide_out_time') }});
                    currentSlide = (currentSlide + 1) % slides.length;
                    $(slides[currentSlide]).animate({
                        'opacity': '1'
                    }, {{ config('app.slide_out_time') }});
                }
            }
        });

        // Check for updates
        let last_update = {{ $last_update }};
        let startup_timestamp = {{ time() }};

        let checkUpdate = setInterval(function() {
            console.log("[MISMANAGER] Checking for updates");

            $.ajax({
                url: '/api/devices/monitor/update',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    last_update: last_update,
                    startup_timestamp: startup_timestamp,
                    presentation_id: {{ $device->presentation_id }},
                    currentSlide: currentSlide,
                    secret: '{{ $device->secret }}'
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.last_update != last_update || data.presentation_id !=
                        {{ $device->presentation_id }}) {

                        console.log("[MISMANAGER] A new update is available, reloading page");

                        location.reload();
                    }
                }
            });
        }, {{ config('app.monitor_check_update_time_seconds') }} * 1000);
    </script>

</body>

</html>
