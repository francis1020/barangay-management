@props(['label' => 'Back', 'url' => null])

@if($url)
    <a href="{{ $url }}" {{ $attributes->merge(['class' => 'btn btn-outline-secondary btn-sm']) }}>
        <i class="bi bi-arrow-left me-1"></i> {{ $label }}
    </a>
@else
    <button type="button" onclick="window.history.back()" {{ $attributes->merge(['class' => 'btn btn-outline-secondary btn-sm']) }}>
        <i class="bi bi-arrow-left me-1"></i> {{ $label }}
    </button>
@endif
