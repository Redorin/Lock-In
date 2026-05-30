@props(['title', 'message' => null])

<div {{ $attributes->merge(['class' => 'empty-rich']) }}>
    @isset($icon)
        {{ $icon }}
    @else
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>
    @endisset
    <h3>{{ $title }}</h3>
    @if($message)
        <p>{{ $message }}</p>
    @endif
</div>
