<div wire:poll>
    <div class="subtitle">{{ $presentation->slides->count() }} {{ __('Slides') }}</div>

    @if (!$presentation->processed)
        <div class="notification is-info">
            <style>
                .loader {
                    width: 48px;
                    height: 48px;
                    border: 5px solid #FFF;
                    border-bottom-color: transparent;
                    border-radius: 50%;
                    display: inline-block;
                    box-sizing: border-box;
                    animation: rotation 1s linear infinite;
                }

                @keyframes rotation {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }
            </style>
            <span class="loader"></span>
            <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                class="is-size-4">{{ __('Slides are being processed, please wait...') }}</span>
        </div>
    @endif

    @foreach ($presentation->slides as $slide)
        <div class="slide box">
            <div class="columns gapless">
                <img width="300" src="{{ $slide->publicpreviewpath() }}">
                <div class="column">
                    <div>{{ __('Order') }}: {{ $slide->order }}</div>
                    <div>{{ __('Created') }}: {{ $slide->created_at }}</div>
                    <div>{{ __('Filename') }}: {{ $slide->name_on_disk }} </div>
                    {{-- <div>Name: {{ $slide->name }}</div> --}}
                    <div>&nbsp;</div>
                    <div><a target="_blank" href="{{ $slide->publicpath() }}">{{ __('Preview') }}</a></div>
                </div>

                <div class="column">

                </div>

                @can('delete slides')
                    <form action="{{ route('slides.destroy', $slide->id) }}" method="POST"
                        onsubmit="return confirm('{{ __('Are you sure to delete this slide?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button is-danger is-smalls"><i class="mdi mdi-trash-can"></i></button>
                    </form>
                @endcan
            </div>
        </div>
    @endforeach
</div>
