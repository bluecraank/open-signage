@props(['action', 'confirm', 'label' => null, 'size' => 'sm'])

{{-- Every delete asks for confirmation first --}}
<form action="{{ $action }}" method="POST" class="d-inline" onsubmit="return confirm(@js($confirm))">
    @csrf
    @method('DELETE')
    @if ($label)
        <button type="submit" class="btn btn-outline-danger btn-{{ $size }}"><i class="bi bi-trash me-1"></i>{{ $label }}</button>
    @else
        <button type="submit" class="btn btn-outline-danger btn-{{ $size }} icon-btn" title="{{ __('Delete') }}"
            aria-label="{{ __('Delete') }}"><i class="bi bi-trash"></i></button>
    @endif
</form>
