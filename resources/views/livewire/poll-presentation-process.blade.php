<div wire:poll.10s>
    @if ($currentPresentation)
        <span class="processing_info is-flex is-align-content-center">
            <span class="loader"></span>
            <span class="ml-2 is-">{{ __('Processing') }} {{ $currentPresentation->name }}</span>
        </span>
    @endif
</div>
