@extends('admin.layout')
@section('title','QR Codes')
@section('page-title','Daily QR Codes')
@section('page-sub','Today\'s check-in QR codes — reset automatically at midnight')

@section('styles')
<style>
    .qr-note {
        background:rgba(79,156,249,.06); border:1px solid rgba(79,156,249,.15);
        border-radius:var(--rm); padding:14px 18px;
        font-size:.83rem; color:var(--soft);
        display:flex; align-items:flex-start; gap:10px;
        margin-bottom:20px;
    }
    .qr-note svg { width:16px; height:16px; color:var(--accent2); flex-shrink:0; margin-top:1px; }

    .qr-grid {
        display:grid;
        grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));
        gap:16px;
    }
    .qr-card {
        background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08);
        border-radius:var(--rl); padding:20px; text-align:center;
        backdrop-filter:blur(16px); box-shadow:inset 0 1px 0 rgba(255,255,255,.07);
        transition:border-color .2s;
    }
    .qr-card:hover { border-color:rgba(79,156,249,.25); }

    .qr-building { font-size:.65rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); margin-bottom:6px; }
    .qr-name     { font-size:.95rem; font-weight:700; margin-bottom:14px; }

    .qr-img-wrap {
        background:#fff; border-radius:10px; padding:10px;
        display:inline-block; margin-bottom:14px;
        box-shadow:0 4px 20px rgba(0,0,0,.3);
    }
    .qr-img-wrap img { width:160px; height:160px; display:block; }

    .qr-occ {
        display:flex; align-items:center; justify-content:center; gap:8px;
        font-size:.8rem; color:var(--soft); margin-bottom:14px;
    }
    .qr-occ-bar { flex:1; height:4px; background:rgba(255,255,255,.07); border-radius:99px; overflow:hidden; max-width:80px; }
    .qr-occ-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#4f9cf9,#7eb8ff); }

    .qr-date { font-size:.72rem; color:var(--muted); }

    .print-btn {
        display:inline-flex; align-items:center; gap:6px;
        padding:8px 16px; background:linear-gradient(135deg,#4f9cf9,#1a6fe8);
        color:#fff; border:none; border-radius:var(--rs);
        font-family:'Outfit',sans-serif; font-size:.78rem; font-weight:600;
        cursor:pointer; text-decoration:none; margin-top:10px;
        transition:opacity .15s;
    }
    .print-btn:hover { opacity:.85; }
    .print-btn svg { width:13px; height:13px; }

    .building-section { margin-bottom:28px; }
    .bh { display:flex; align-items:center; gap:10px; margin-bottom:14px; }
    .bt { font-size:.68rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); }
    .bl { flex:1; height:1px; background:rgba(255,255,255,.07); }

    @media print {
        .qr-card { break-inside:avoid; border:1px solid #ccc; background:#fff; color:#000; }
        .qr-building,.qr-name,.qr-date { color:#000; }
        .qr-note,.print-btn,.sidebar,.topbar,.abadge { display:none!important; }
    }
</style>
@endsection

@section('content')

<div class="qr-note">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    These QR codes are valid for <strong style="color:var(--accent2);">today only ({{ now()->format('F j, Y') }})</strong> and will automatically rotate at midnight. Print or display them at each space entrance.
</div>

@php $grouped = $spaces->groupBy('building'); @endphp

@foreach($grouped as $building => $bspaces)
<div class="building-section">
    <div class="bh"><span class="bt">{{ $building }}</span><div class="bl"></div></div>
    <div class="qr-grid">
        @foreach($bspaces as $space)
        <div class="qr-card">
            <div class="qr-building">{{ $space->building }}</div>
            <div class="qr-name">{{ $space->name }}</div>

            {{-- QR Code using Google Charts API --}}
            <div class="qr-img-wrap">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($space->checkinUrl()) }}&bgcolor=ffffff&color=04080f&margin=4"
                     alt="QR for {{ $space->name }}"
                     width="160" height="160">
            </div>

            <div class="qr-occ">
                <span>{{ $space->current_occupancy }}/{{ $space->capacity }}</span>
                <div class="qr-occ-bar">
                    <div class="qr-occ-fill" style="width:{{ $space->occupancy_percent }}%"></div>
                </div>
                <span class="sbadge {{ in_array($space->status,['LOW','MODERATE']) ? 'sa' : 'si' }}">{{ $space->status }}</span>
            </div>

            <div class="qr-date">Valid: {{ now()->format('M j, Y') }}</div>
        </div>
        @endforeach
    </div>
</div>
@endforeach

@endsection