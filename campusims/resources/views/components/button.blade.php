@props([
    'variant' => 'ghost',
    'href' => null,
    'type' => 'button',
])

@php
    $classes = [
        'blue' => 'btn-blue',
        'danger' => 'btn-danger',
        'ghost' => 'btn-ghost',
        'success' => 'btn-success',
        'warn' => 'btn-warn',
    ];

    $variantClass = $classes[$variant] ?? $classes['ghost'];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "btn {$variantClass}"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "btn {$variantClass}"]) }}>
        {{ $slot }}
    </button>
@endif
