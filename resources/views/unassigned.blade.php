<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    {{-- Check again until the monitor has something to show --}}
    <meta http-equiv="refresh" content="5">
    <style>
        html, body {
            margin: 0;
            height: 100%;
            background: radial-gradient(80vw 60vh at 50% 40%, #1d2333, #0d1017);
            color: #c9cfdd;
            font-family: "Segoe UI", system-ui, -apple-system, Roboto, Arial, sans-serif;
            cursor: none;
        }
        .container {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 2vh;
        }
        .text {
            font-size: 5vw;
            font-weight: 600;
            letter-spacing: -.02em;
        }
        .hint {
            font-size: 1.6vw;
            color: #7f879b;
        }
        .dot {
            width: 1.2vw;
            height: 1.2vw;
            border-radius: 50%;
            background: #e0a127;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(224, 161, 39, .6); }
            70% { box-shadow: 0 0 0 2vw rgba(224, 161, 39, 0); }
            100% { box-shadow: 0 0 0 0 rgba(224, 161, 39, 0); }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="dot"></div>
        <div class="text">
            @if ($error == 'no_pres_assigned')
                {{ __('No presentation assigned') }}
            @elseif($error == 'no_device')
                {{ __('Device not found') }}
            @elseif($error == 'not_registered')
                {{ __('Device not registered') }}
            @elseif($error == 'no_slides')
                {{ __('Presentation has no slides') }}
            @endif
        </div>
        <div class="hint">{{ config('app.name') }} · {{ request()->ip() }}</div>
    </div>
</body>

</html>
