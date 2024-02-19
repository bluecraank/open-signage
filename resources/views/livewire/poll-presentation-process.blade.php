<div wire:poll.10s>
    @foreach ($currentPresentations as $presentation)
        <span class="processing_info d-flex justify-content-center align-items-center">
            <span class="loader"></span>
            <span class="mx-1">{{ __('Processing') }} {{ $presentation->name }}
                ({{ $presentation->slides()->count() }}/{{ $presentation->total_slides }})</span>
        </span>
    @endforeach
</div>
