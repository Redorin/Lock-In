@props([
    'variant' => 'admin',
    'label',
    'value',
    'accent' => false,
    'main' => false,
    'valueColor' => null,
])

@if($variant === 'student')
    <div {{ $attributes->merge(['class' => 'stat' . ($main ? ' stat-main' : '')]) }}>
        <div class="stat-top-row">
            <div class="stat-icon">{{ $icon }}</div>
            <div class="stat-btn">
                @isset($action)
                    {{ $action }}
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                @endisset
            </div>
        </div>
        <div>
            <div class="stat-lbl">{{ $label }}</div>
            <div class="stat-val" @if($valueColor) style="color:{{ $valueColor }};" @endif>{{ $value }}</div>
        </div>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'sc']) }}>
        <div class="sc-icon">{{ $icon }}</div>
        <div class="sl">{{ $label }}</div>
        <div class="sv {{ $accent ? 'accent' : '' }}">{{ $value }}</div>
    </div>
@endif
