@extends('student.layout')

@section('title', 'Dashboard')
@section('page-title', 'Live Space Availability')
@section('page-sub', 'Real-time occupancy across all campus buildings')

@section('styles')
<style>
    .summary-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-card {
        background: var(--glass);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        padding: 18px 20px;
        backdrop-filter: blur(16px);
    }
    .stat-label { font-size: .72rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px; }
    .stat-value { font-size: 1.6rem; font-weight: 800; letter-spacing: -.5px; }
    .stat-value.green  { color: var(--accent); }
    .stat-value.purple { color: var(--accent2); }
    .stat-value.yellow { color: #c8f04d; }

    .building-section { margin-bottom: 24px; }
    .building-header {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 12px;
    }
    .building-tag {
        font-size: .7rem; font-weight: 700; letter-spacing: .1em;
        text-transform: uppercase; color: var(--text-muted);
    }
    .building-line { flex: 1; height: 1px; background: var(--glass-border); }

    .spaces-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
    }

    .space-card {
        background: var(--glass);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        overflow: hidden;
        backdrop-filter: blur(16px);
        transition: transform .2s, border-color .2s, box-shadow .2s;
        animation: fadeUp .5s ease both;
        cursor: default;
    }
    .space-card:hover {
        transform: translateY(-3px);
        border-color: rgba(0,229,160,.2);
        box-shadow: 0 8px 32px rgba(0,0,0,.3);
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .space-thumb {
        height: 110px;
        background: linear-gradient(135deg, rgba(255,255,255,.04) 0%, rgba(255,255,255,.02) 100%);
        display: flex; align-items: center; justify-content: center;
        border-bottom: 1px solid var(--glass-border);
        position: relative; overflow: hidden;
    }
    .space-thumb::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at center, rgba(0,229,160,.05) 0%, transparent 70%);
    }
    .space-thumb svg { width: 32px; height: 32px; opacity: .2; color: var(--accent); }

    .space-body { padding: 14px; }
    .space-name { font-size: .9rem; font-weight: 600; margin-bottom: 10px; color: var(--text); }

    .space-meta {
        display: flex; align-items: center;
        justify-content: space-between; margin-bottom: 8px;
    }
    .space-count { font-size: .82rem; font-weight: 600; color: var(--text-soft); }

    .badge {
        font-size: .62rem; font-weight: 700; letter-spacing: .08em;
        padding: 3px 9px; border-radius: 99px;
    }
    .badge-low      { background: rgba(0,229,160,.15);  color: #00e5a0; border: 1px solid rgba(0,229,160,.2); }
    .badge-moderate { background: rgba(251,191,36,.12); color: #fbbf24; border: 1px solid rgba(251,191,36,.2); }
    .badge-high     { background: rgba(239,68,68,.12);  color: #f87171; border: 1px solid rgba(239,68,68,.2); }
    .badge-full     { background: rgba(127,29,29,.3);   color: #fca5a5; border: 1px solid rgba(239,68,68,.3); }

    .progress-track {
        height: 3px; background: rgba(255,255,255,.07);
        border-radius: 99px; overflow: hidden;
    }
    .progress-fill {
        height: 100%; border-radius: 99px;
        transition: width .8s cubic-bezier(.4,0,.2,1);
    }
    .fill-low      { background: linear-gradient(90deg, #00e5a0, #00b87a); }
    .fill-moderate { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
    .fill-high     { background: linear-gradient(90deg, #f87171, #ef4444); }
    .fill-full     { background: linear-gradient(90deg, #fca5a5, #ef4444); }

    /* stagger */
    .space-card:nth-child(1){animation-delay:.04s}
    .space-card:nth-child(2){animation-delay:.08s}
    .space-card:nth-child(3){animation-delay:.12s}
    .space-card:nth-child(4){animation-delay:.16s}
    .space-card:nth-child(5){animation-delay:.20s}
    .space-card:nth-child(6){animation-delay:.24s}
    .space-card:nth-child(7){animation-delay:.28s}
    .space-card:nth-child(8){animation-delay:.32s}
</style>
@endsection

@section('content')

{{-- Summary stats --}}
@php
    $total   = $spaces->count();
    $low     = $spaces->where('status', 'LOW')->count();
    $crowded = $spaces->whereIn('status', ['HIGH','FULL'])->count();
@endphp

<div class="summary-row">
    <div class="stat-card">
        <div class="stat-label">Total Spaces</div>
        <div class="stat-value purple">{{ $total }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Available</div>
        <div class="stat-value green">{{ $low }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Crowded</div>
        <div class="stat-value yellow">{{ $crowded }}</div>
    </div>
</div>

{{-- Grouped by building --}}
@forelse($grouped as $building => $buildingSpaces)
<div class="building-section">
    <div class="building-header">
        <span class="building-tag">{{ $building }}</span>
        <div class="building-line"></div>
    </div>
    <div class="spaces-grid">
        @foreach($buildingSpaces as $space)
        <div class="space-card">
            <div class="space-thumb">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <div class="space-body">
                <div class="space-name">{{ $space->name }}</div>
                <div class="space-meta">
                    <span class="space-count">{{ $space->current_occupancy }} / {{ $space->capacity }}</span>
                    <span class="badge badge-{{ strtolower($space->status) }}">{{ $space->status }}</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill fill-{{ strtolower($space->status) }}"
                         style="width:{{ $space->occupancy_percent }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@empty
<div style="text-align:center;padding:60px 20px;color:var(--text-muted);">
    <p style="font-size:.9rem;">No spaces available yet. Check back soon.</p>
</div>
@endforelse

@endsection