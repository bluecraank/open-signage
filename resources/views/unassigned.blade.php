<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error - MIS Manager</title>
    <meta http-equiv='refresh' content='5'>
</head>

<body>
    <style>
        body {
            background-color: #efefef;
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
        }
        .container {
            display: flex;
            height: 100%;
            width: 100%;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .text {
            font-size: 6rem;
            color: #9c9c9c;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
    <div class="container">
        <div class="text">
            @if ($error == 'no_pres_assigned')
                {{ __('No presentation assigned') }}
            @elseif($error == 'no_device')
                {{ __('Device not found') }}
            @elseif($error == 'not_registered')
                {{ __('Device not registered') }}
            @endif
        </div>
    </div>
</body>

</html>
