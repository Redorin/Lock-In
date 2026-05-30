@props(['type' => 'success'])

@php
    $classes = [
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'danger' => 'alert-danger',
    ];

    $class = $classes[$type] ?? $classes['success'];
@endphp

<div {{ $attributes->merge(['class' => "alert {$class}", 'role' => 'alert']) }}>
    @isset($icon)
        {{ $icon }}
    @else
        @if(in_array($type, ['error', 'danger'], true))
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        @else
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        @endif
    @endisset
    {{ $slot }}
</div>
