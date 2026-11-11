@props(['icon' => 'inbox', 'text'])

<div class="empty-state">
    <i class="bi bi-{{ $icon }}"></i>
    {{ $text }}
    {{ $slot }}
</div>
