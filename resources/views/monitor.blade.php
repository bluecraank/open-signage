<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $device->name }} | Monitor: {{ $device->presentation?->name }} | {{ count($slides) }} Slides</title>
</head>

<body id="slide">
    <style>
        body,
        html {
            padding: 0;
            margin: 0;
            height: 100%;
            width: 100%;
        }

        .slides {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            overflow: hidden !important;
            z-index: 10;
            opacity: 1;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            width: 100%;
            height: inherit;
        }

        .lds-ripple {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }

        .lds-ripple div {
            position: absolute;
            border: 4px solid #fff;
            opacity: 1;
            border-radius: 50%;
            animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }

        .lds-ripple div:nth-child(2) {
            animation-delay: -0.5s;
        }

        @keyframes lds-ripple {
            0% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 0;
            }

            4.9% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 0;
            }

            5% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 1;
            }

            100% {
                top: 0px;
                left: 0px;
                width: 72px;
                height: 72px;
                opacity: 0;
            }
        }

        .loading {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #acacac;
            display: flex;
        }

        .loading .container {
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;
        }
    </style>

    <div class="loading">
        <div class="container">
            <div class="lds-ripple">
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <div class="slides">
        @php $i = 0 @endphp
        @foreach ($slides as $slide)
            <div class="slide" style="opacity:@if($i == 0) 1 @else 0 @endif;background-image: url('{{ $slide }}')">
            </div>
            @php $i++ @endphp
        @endforeach
    </div>

    <script src="/data/js/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".loading").remove();
            let currentSlide = 0;

            let interval = {{ $interval ?? 20000 }};

            var slides = document.querySelectorAll('.slide');

            var slideInterval = setInterval(nextSlide, interval);

            function nextSlide() {
                $(".loading").remove();

                if(slides.length == 1) return;

                console.log('Triggering next slide');
                if (document.hasFocus()) {
                    $(slides[currentSlide]).animate({
                        'opacity': '0'
                    }, 1800);
                    currentSlide = (currentSlide + 1) % slides.length;
                    $(slides[currentSlide]).animate({
                        'opacity': '1'
                    }, 1200);
                }
            }
        });
    </script>

    <script>
        let last_update = {{ $last_update }};
        let checkUpdate = setInterval(function() {
            $.ajax({
                url: '/devices/monitor/{{ $device->id }}/update',
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.last_update != last_update || data.presentation_id != {{ $device->presentation_id }}) {
                        console.log("A new update is available, reloading page");
                        location.reload();
                    }
                }
            });
        }, 60 * 1000);
    </script>

    <script>
        // Refresh page every hour
        setInterval(location.reload, 60 * 60 * 1000)
    </script>

</body>

</html>
