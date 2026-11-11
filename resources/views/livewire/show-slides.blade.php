<div @if (!$presentation->processed) wire:poll.3s @endif>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h5 mb-0">
            {{ __('Slides') }}
            <span class="badge rounded-pill count-badge ms-1">
                @if (!$presentation->processed && $presentation->total_slides)
                    {{ $presentation->slides->count() }}/{{ $presentation->total_slides }}
                @else
                    {{ $presentation->slides->count() }}
                @endif
            </span>
        </h2>
    </div>

    @if (!$presentation->processed)
        <div class="alert alert-info align-items-center">
            <span class="loader"></span>
            <div>{{ __('Slides are being processed, please wait...') }}</div>
        </div>
    @endif

    <div class="row g-3">
        @foreach ($presentation->slides as $slide)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card slide-card position-relative">
                    <span class="badge text-bg-dark slide-order">#{{ $slide->order }}</span>
                    @if ($slide->type === 'video')
                        <video src="{{ $slide->publicpath() }}" class="card-img-top" controls muted preload="metadata"></video>
                    @else
                        <a href="{{ $slide->publicpath() }}" target="_blank">
                            <img src="{{ $slide->publicpreviewpath() }}" class="card-img-top" alt="" loading="lazy">
                        </a>
                    @endif
                    <div class="card-body d-flex justify-content-between align-items-center py-2">
                        <div class="small text-muted text-truncate" title="{{ $slide->name_on_disk }}">
                            <i class="bi bi-{{ $slide->type === 'video' ? 'film' : 'image' }} me-1"></i>{{ $slide->created_at?->format('d.m.Y H:i') }}
                        </div>
                        <div class="d-flex gap-1">
                            <a class="btn btn-outline-secondary btn-sm icon-btn" target="_blank" href="{{ $slide->publicpath() }}"
                                title="{{ __('Preview') }}"><i class="bi bi-box-arrow-up-right"></i></a>
                            @can('delete slides')
                                <x-delete-button :action="route('slides.destroy', $slide->id)"
                                    :confirm="__('Are you sure to delete this slide?')" />
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
