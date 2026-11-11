@props(['title', 'subtitle' => null, 'back' => null, 'backLabel' => null])

<div class="page-header">
    <div>
        @if ($back)
            <nav class="breadcrumb">
                <a href="{{ $back }}"><i class="bi bi-arrow-left"></i> {{ $backLabel }}</a>
            </nav>
        @endif
        <h1>{{ $title }}</h1>
        @if ($subtitle)
            <p class="page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    @if (isset($actions) && trim($actions) !== '')
        <div class="d-flex flex-wrap gap-2">{{ $actions }}</div>
    @endif
</div>
