@extends('student.layout')

@section('title', 'Campus Map')
@section('page-title', 'Campus Map')
@section('page-sub', 'Explore buildings and spaces around campus')

@section('content')
<div style="
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    min-height: 420px;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 28px; backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
    box-shadow: var(--shadow-md), var(--inset);
    text-align: center; padding: 56px 40px;
    animation: pageIn .5s var(--ease) both;
    transition: background var(--t) var(--ease), border-color var(--t) var(--ease);
">
    <div style="
        width: 70px; height: 70px; border-radius: 20px;
        background: var(--accent-bg); border: 1px solid var(--accent-border);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 22px;
        box-shadow: 0 4px 20px var(--accent-glow);
    ">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--accent2)" stroke-width="1.5">
            <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
            <line x1="9" y1="3" x2="9" y2="18"/>
            <line x1="15" y1="6" x2="15" y2="21"/>
        </svg>
    </div>
    <h2 style="font-family:'Plus Jakarta Sans',sans-serif; font-size:1.3rem; font-weight:800; letter-spacing:-.3px; margin-bottom:10px; color:var(--text);">Campus Map Coming Soon</h2>
    <p style="font-size:.875rem; color:var(--text-soft); max-width:300px; line-height:1.75; margin-bottom:24px;">
        An interactive map showing all campus buildings and real-time space availability will be live here soon.
    </p>
    <a href="{{ route('student.dashboard') }}" style="
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 22px;
        background: var(--accent-bg); border: 1px solid var(--accent-border);
        color: var(--accent2); border-radius: 99px;
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: .87rem; font-weight: 700;
        text-decoration: none; transition: all var(--t) var(--ease);
    " onmouseover="this.style.background='var(--accent)';this.style.color='#fff';this.style.transform='translateY(-2px)'"
       onmouseout="this.style.background='var(--accent-bg)';this.style.color='var(--accent2)';this.style.transform=''">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Dashboard
    </a>
</div>
@endsection