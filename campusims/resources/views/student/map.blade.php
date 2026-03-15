@extends('student.layout')

@section('title', 'Campus Map')
@section('page-title', 'Campus Map')
@section('page-sub', 'Explore buildings and spaces around campus')

@section('content')
<div style="
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    min-height:420px;
    background:var(--glass); border:1px solid var(--glass-border);
    border-radius:var(--radius-lg); backdrop-filter:blur(16px);
    text-align:center; padding:56px 40px;
">
    <div style="
        width:70px;height:70px;border-radius:18px;
        background:rgba(0,229,160,.08);border:1px solid rgba(0,229,160,.15);
        display:flex;align-items:center;justify-content:center;margin-bottom:22px;
    ">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" opacity=".7">
            <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
            <line x1="9" y1="3" x2="9" y2="18"/>
            <line x1="15" y1="6" x2="15" y2="21"/>
        </svg>
    </div>
    <h2 style="font-size:1.2rem;font-weight:700;margin-bottom:8px;">Campus Map Coming Soon</h2>
    <p style="font-size:.85rem;color:var(--text-soft);max-width:300px;line-height:1.7;">
        An interactive map showing all campus buildings and real-time space availability will be live here soon.
    </p>
</div>
@endsection