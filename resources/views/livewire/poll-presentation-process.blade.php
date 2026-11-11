<div wire:poll.10s class="d-flex flex-column gap-1">
    @foreach ($currentPresentations as $presentation)
        <a href="{{ route('presentations.show', $presentation->id) }}"
            class="processing_info d-flex align-items-center gap-2 text-decoration-none">
            <span class="loader"></span>
            <span>{{ __('Processing') }} {{ Str::limit($presentation->name, 24) }}
                ({{ $presentation->slides()->count() }}@if ($presentation->total_slides)/{{ $presentation->total_slides }}@endif)</span>
        </a>
    @endforeach
</div>
