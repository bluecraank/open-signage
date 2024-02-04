<div wire:poll>
    <div class="subtitle">{{ $presentation->slides->count() }}@if (!$presentation->processed)
            /{{ $presentation->total_slides }}
        @endif {{ __('Slides') }}</div>

    @if (!$presentation->processed)
        <div class="alert alert-info">
            <style>
                .loader1 {
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
            <span class="loader1"></span>
            <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                class="is-size-4">{{ __('Slides are being processed, please wait...') }}</span>
        </div>
    @endif

    <div class="row">
        @foreach ($presentation->slides as $slide)
            <div class="col-auto mb-3 d-flex justify-content-center">
                <div class="card d-flex justify-content-center" style="width: 18rem;">
                    <img src="{{ $slide->publicpreviewpath() }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div>{{ __('Order') }}: {{ $slide->order }}</div>
                        <div>{{ __('Created') }}: {{ $slide->created_at }}</div>
                        <div>{{ __('Filename') }}: {{ $slide->name_on_disk }} </div>
                        <div><a target="_blank" href="{{ $slide->publicpath() }}">{{ __('Preview') }}</a></div>
                        @can('delete slides')
                            <form action="{{ route('slides.destroy', $slide->id) }}" method="POST"
                                onsubmit="return confirm('{{ __('Are you sure to delete this slide?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm float-end"><i
                                        class="bi-trash"></i></button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @endforeach
    </div>
