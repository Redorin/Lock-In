@extends('layout')

@section('title', 'Dashboard')

@section('content')
<div class="auth-card" style="max-width:500px;text-align:center;">

    <div style="width:56px;height:56px;background:var(--ink);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
        <svg width="28" height="28" viewBox="0 0 20 20" fill="none">
            <path d="M10 2L3 6v8l7 4 7-4V6L10 2z" stroke="#c8f04d" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M10 2v12M3 6l7 4 7-4" stroke="#c8f04d" stroke-width="1.6" stroke-linejoin="round"/>
        </svg>
    </div>

    <h1 class="auth-title">Welcome back{{ auth()->user() ? ', ' . auth()->user()->name : '' }}.</h1>
    <p class="auth-sub" style="margin-bottom:32px;">You're logged in to CampuSIMS.</p>

    <a href="{{ route('students.index') }}"
       style="display:inline-block;padding:11px 24px;background:var(--ink);color:var(--accent);font-family:'Syne',sans-serif;font-weight:700;font-size:.95rem;border-radius:var(--radius);text-decoration:none;margin-bottom:12px;width:100%;box-sizing:border-box;">
        View Students →
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            style="width:100%;padding:11px;background:transparent;border:1.5px solid var(--border);border-radius:var(--radius);font-family:'DM Sans',sans-serif;font-size:.95rem;color:var(--ink-muted);cursor:pointer;transition:border-color .2s;">
            Sign out
        </button>
    </form>

</div>
@endsection