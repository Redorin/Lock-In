@extends('student.layout')
@section('title','Check-in Result')
@section('page-title','Check-in')
@section('page-sub','Scan result')

@section('styles')
<style>
    .result-wrap {
        display:flex; flex-direction:column; align-items:center;
        justify-content:center; min-height:400px;
        max-width:400px; margin:0 auto; text-align:center; gap:0;
    }
    .result-card {
        width:100%; background:rgba(255,255,255,.04);
        border:1px solid rgba(255,255,255,.08);
        border-radius:var(--rl); padding:40px 32px;
        backdrop-filter:blur(16px);
        box-shadow:inset 0 1px 0 rgba(255,255,255,.07);
        animation:cardIn .5s cubic-bezier(.22,1,.36,1) both;
    }
    @keyframes cardIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}

    .result-icon {
        width:72px; height:72px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        margin:0 auto 20px; font-size:2rem;
    }
    .icon-success { background:rgba(79,156,249,.12); border:2px solid rgba(79,156,249,.25); }
    .icon-error   { background:rgba(248,113,113,.10); border:2px solid rgba(248,113,113,.2); }
    .icon-warn    { background:rgba(251,191,36,.10);  border:2px solid rgba(251,191,36,.2); }

    .result-title { font-size:1.3rem; font-weight:800; letter-spacing:-.3px; margin-bottom:8px; }
    .result-msg   { font-size:.875rem; color:var(--soft); line-height:1.6; margin-bottom:24px; }

    .space-badge {
        display:inline-flex; align-items:center; gap:8px;
        background:rgba(79,156,249,.08); border:1px solid rgba(79,156,249,.15);
        border-radius:99px; padding:8px 18px;
        font-size:.85rem; font-weight:600; color:var(--accent2);
        margin-bottom:24px;
    }
    .space-badge svg { width:15px; height:15px; }

    .checkin-info {
        background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.07);
        border-radius:12px; padding:16px; margin-bottom:24px; text-align:left;
    }
    .ci-row { display:flex; justify-content:space-between; align-items:center; padding:5px 0; font-size:.82rem; }
    .ci-row:not(:last-child){ border-bottom:1px solid rgba(255,255,255,.05); }
    .ci-label { color:var(--muted); }
    .ci-val   { color:var(--white); font-weight:600; }

    .prev-note {
        background:rgba(251,191,36,.06); border:1px solid rgba(251,191,36,.15);
        border-radius:10px; padding:10px 14px;
        font-size:.78rem; color:rgba(251,191,36,.8);
        margin-bottom:20px; text-align:left;
    }

    .btn-scan {
        width:100%; padding:13px;
        background:linear-gradient(135deg,#4f9cf9,#1a6fe8); color:#fff;
        font-family:'Outfit',sans-serif; font-weight:700; font-size:.95rem;
        border:none; border-radius:var(--rm); cursor:pointer;
        box-shadow:0 4px 20px rgba(79,156,249,.25);
        text-decoration:none; display:block; text-align:center;
        transition:opacity .18s;
    }
    .btn-scan:hover { opacity:.88; }
    .btn-dash {
        width:100%; padding:11px;
        background:transparent; color:var(--soft);
        font-family:'Outfit',sans-serif; font-size:.875rem;
        border:1px solid rgba(255,255,255,.1); border-radius:var(--rm);
        cursor:pointer; margin-top:10px; text-decoration:none; display:block; text-align:center;
    }
</style>
@endsection

@section('content')
<div class="result-wrap">
    <div class="result-card">

        @if($success)
        {{-- ── Success ── --}}
        <div class="result-icon icon-success">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--accent2)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="result-title" style="color:var(--accent2);">Checked In!</div>
        <div class="result-msg">{{ $message }}</div>

        @if($prevSpace ?? false)
        <div class="prev-note">
            ⚡ Auto-checked out of <strong>{{ $prevSpace->building }} — {{ $prevSpace->name }}</strong>
        </div>
        @endif

        @if($space)
        <div class="checkin-info">
            <div class="ci-row"><span class="ci-label">Space</span><span class="ci-val">{{ $space->name }}</span></div>
            <div class="ci-row"><span class="ci-label">Building</span><span class="ci-val">{{ $space->building }}</span></div>
            <div class="ci-row"><span class="ci-label">Checked in at</span><span class="ci-val">{{ now()->format('g:i A') }}</span></div>
            <div class="ci-row"><span class="ci-label">Auto-checkout at</span><span class="ci-val">{{ now()->addHours(2)->format('g:i A') }}</span></div>
            <div class="ci-row"><span class="ci-label">Occupancy</span><span class="ci-val">{{ $space->current_occupancy }} / {{ $space->capacity }}</span></div>
        </div>
        @endif

        <form method="POST" action="{{ route('checkin.checkout') }}" style="margin-bottom:10px;">
            @csrf
            <button type="submit" class="btn-dash">Check Out Now</button>
        </form>
        <a href="{{ route('student.dashboard') }}" class="btn-dash">Back to Dashboard</a>

        @elseif(isset($alreadyIn) && $alreadyIn)
        {{-- ── Already checked in ── --}}
        <div class="result-icon icon-warn">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--warn)" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="result-title" style="color:var(--warn);">Already Here</div>
        <div class="result-msg">{{ $message }}</div>
        <a href="{{ route('student.scanner') }}" class="btn-scan">Back to Scanner</a>
        <a href="{{ route('student.dashboard') }}" class="btn-dash">Dashboard</a>

        @elseif(isset($full) && $full)
        {{-- ── Space full ── --}}
        <div class="result-icon icon-error">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </div>
        <div class="result-title" style="color:var(--danger);">Space Full</div>
        <div class="result-msg">{{ $message }}</div>
        <a href="{{ route('student.scanner') }}" class="btn-scan">Try Another Space</a>
        <a href="{{ route('student.dashboard') }}" class="btn-dash">Dashboard</a>

        @else
        {{-- ── Invalid QR ── --}}
        <div class="result-icon icon-error">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="result-title" style="color:var(--danger);">Invalid QR Code</div>
        <div class="result-msg">{{ $message }}</div>
        <a href="{{ route('student.scanner') }}" class="btn-scan">Scan Again</a>
        <a href="{{ route('student.dashboard') }}" class="btn-dash">Dashboard</a>
        @endif

    </div>
</div>
@endsection