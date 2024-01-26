<div wire:poll.10s>
    @foreach ($currentPresentations as $presentation)
        <span class="processing_info is-flex is-align-content-center mt-1 mb-1">
            <span class="loader"></span>
            <span class="ml-2 is-">{{ __('Processing') }} {{ $presentation->name }}
                ({{ $presentation->slides()->count() }}/{{ $presentation->total_slides }})</span>
        </span>
    @endforeach
</div>
