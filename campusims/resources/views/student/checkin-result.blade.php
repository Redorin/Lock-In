@extends('student.layout')
@section('title','Check-in Result')
@section('page-title','Check-in')
@section('page-sub','Scan result')

@section('styles')
<style>
    .result-wrap {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; min-height: 400px;
        max-width: 420px; margin: 0 auto; text-align: center; gap: 0;
    }
    .result-card {
        width: 100%; background: var(--surface);
        border: 1px solid var(--border2);
        border-radius: 28px; padding: 40px 32px;
        backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
        box-shadow: var(--shadow-md), var(--inset);
        animation: cardIn .5s var(--ease) both;
        transition: background var(--t) var(--ease), border-color var(--t) var(--ease);
    }
    @keyframes cardIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

    .result-icon {
        width: 72px; height: 72px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
    }
    .icon-success { background: var(--accent-bg); border: 2px solid var(--accent-border); }
    .icon-error   { background: var(--danger-bg); border: 2px solid var(--danger-border); }
    .icon-warn    { background: var(--warn-bg);   border: 2px solid var(--warn-border); }

    .result-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.4rem; font-weight: 800; letter-spacing: -.4px;
        margin-bottom: 8px; color: var(--text);
    }
    .result-msg {
        font-size: .875rem; color: var(--text-soft);
        line-height: 1.65; margin-bottom: 24px;
    }

    .space-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--accent-bg); border: 1px solid var(--accent-border);
        border-radius: 99px; padding: 8px 18px;
        font-size: .85rem; font-weight: 600; color: var(--accent2);
        margin-bottom: 24px;
    }
    .space-badge svg { width: 15px; height: 15px; }

    .checkin-info {
        background: var(--surface2); border: 1px solid var(--border);
        border-radius: 16px; padding: 16px; margin-bottom: 24px;
        text-align: left;
        transition: background var(--t) var(--ease);
    }
    .ci-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 7px 0; font-size: .83rem;
    }
    .ci-row:not(:last-child) { border-bottom: 1px solid var(--border); }
    .ci-label { color: var(--text-muted); font-weight: 500; }
    .ci-val   { color: var(--text); font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif; }

    .prev-note {
        background: var(--warn-bg); border: 1px solid var(--warn-border);
        border-radius: 12px; padding: 11px 14px;
        font-size: .8rem; color: var(--warn);
        margin-bottom: 20px; text-align: left;
        display: flex; align-items: flex-start; gap: 8px;
    }
    .prev-note svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; }

    .btn-primary {
        width: 100%; padding: 14px;
        background: linear-gradient(135deg, var(--accent), #6366f1); color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: .95rem;
        border: none; border-radius: 99px; cursor: pointer;
        box-shadow: 0 4px 20px var(--accent-glow);
        text-decoration: none; display: block; text-align: center;
        transition: all var(--t) var(--ease);
        margin-bottom: 10px;
        position: relative; overflow: hidden;
    }
    .btn-primary::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(255,255,255,.12) 0%, transparent 100%);
        pointer-events: none;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 28px var(--accent-glow); }
    .btn-primary:active { transform: translateY(0) scale(.98); }

    .btn-ghost {
        width: 100%; padding: 13px;
        background: var(--surface2); color: var(--text-soft);
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: .875rem; font-weight: 600;
        border: 1px solid var(--border2); border-radius: 99px;
        cursor: pointer; text-decoration: none; display: block; text-align: center;
        transition: all var(--t) var(--ease);
        margin-bottom: 10px;
    }
    .btn-ghost:hover { background: var(--surface3); color: var(--text); border-color: var(--border2); }

    .btn-danger-ghost {
        width: 100%; padding: 13px;
        background: var(--danger-bg); color: var(--danger);
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: .875rem; font-weight: 700;
        border: 1px solid var(--danger-border); border-radius: 99px;
        cursor: pointer; display: block; text-align: center;
        transition: all var(--t) var(--ease);
        margin-bottom: 10px;
    }
    .btn-danger-ghost:hover { background: var(--danger); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(248,113,113,.3); }

    .divider { height: 1px; background: var(--border); margin: 20px 0; }

    @media(max-width:480px) {
        .result-card { padding: 28px 20px; border-radius: 24px; }
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
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Auto-checked out of <strong>{{ $prevSpace->building }} — {{ $prevSpace->name }}</strong>
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

        <form method="POST" action="{{ route('checkin.checkout') }}">
            @csrf
            <button type="submit" class="btn-danger-ghost">Check Out Now</button>
        </form>
        <a href="{{ route('student.dashboard') }}" class="btn-ghost">Back to Dashboard</a>

        @elseif(isset($alreadyIn) && $alreadyIn)
        {{-- ── Already checked in ── --}}
        <div class="result-icon icon-warn">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--warn)" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="result-title" style="color:var(--warn);">Already Here</div>
        <div class="result-msg">{{ $message }}</div>
        <a href="{{ route('student.scanner') }}" class="btn-primary">Back to Scanner</a>
        <a href="{{ route('student.dashboard') }}" class="btn-ghost">Dashboard</a>

        @elseif(isset($full) && $full)
        {{-- ── Space full ── --}}
        <div class="result-icon icon-error">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </div>
        <div class="result-title" style="color:var(--danger);">Space Full</div>
        <div class="result-msg">{{ $message }}</div>
        <a href="{{ route('student.scanner') }}" class="btn-primary">Try Another Space</a>
        <a href="{{ route('student.dashboard') }}" class="btn-ghost">Dashboard</a>

        @else
        {{-- ── Invalid QR ── --}}
        <div class="result-icon icon-error">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="result-title" style="color:var(--danger);">Invalid QR Code</div>
        <div class="result-msg">{{ $message }}</div>
        <a href="{{ route('student.scanner') }}" class="btn-primary">Scan Again</a>
        <a href="{{ route('student.dashboard') }}" class="btn-ghost">Dashboard</a>
        @endif

    </div>
</div>
@endsection