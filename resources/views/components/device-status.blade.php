@props(['device', 'active' => null])

@php
    $active ??= $device->isActive();
    $lastSeen = $device->last_seen ? \Carbon\Carbon::parse($device->last_seen)->diffForHumans() : null;
@endphp

<div>
    @if (!$device->registered)
        <span class="status status-pending">{{ __('Waiting for registration...') }}</span>
    @elseif ($active)
        <span class="status status-online">{{ __('Online') }}</span>
        <div class="small text-muted mt-1">{{ $lastSeen }}</div>
    @else
        <span class="status status-offline">{{ __('Offline') }}</span>
        <div class="small text-muted mt-1">{{ $lastSeen ?? __('Never connected') }}</div>
    @endif
</div>
