@extends('admin.layout')
@section('title','Users')
@section('page-title','Manage Users')
@section('page-sub','All approved student accounts')

@section('styles')
<style>
    .users-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px;}
    .user-card{background:var(--glass);border:1px solid var(--glass-border);border-radius:var(--radius-lg);backdrop-filter:blur(16px);padding:22px;transition:border-color .2s,transform .2s;animation:fadeUp .4s ease both;}
    .user-card:hover{border-color:rgba(124,111,247,.25);transform:translateY(-2px);}
    @keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
    .user-card-top{display:flex;align-items:center;gap:14px;margin-bottom:16px;}
    .user-avatar-big{width:50px;height:50px;border-radius:50%;flex-shrink:0;background:linear-gradient(135deg,var(--accent2),#5147c9);display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;color:#fff;border:2px solid rgba(124,111,247,.3);}
    .user-avatar-big.inactive{background:linear-gradient(135deg,#444,#222);filter:grayscale(1);}
    .user-name{font-size:.95rem;font-weight:700;color:var(--text);}
    .user-email{font-size:.75rem;color:var(--text-muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;}
    .user-id-badge{display:inline-block;background:rgba(0,229,160,.08);border:1px solid rgba(0,229,160,.15);color:var(--accent);font-size:.72rem;font-weight:600;letter-spacing:.04em;padding:4px 10px;border-radius:99px;margin-bottom:14px;}
    .user-card-actions{display:flex;gap:8px;}
    .user-card-actions .btn{flex:1;justify-content:center;font-size:.78rem;padding:7px 10px;}
    .results-info{font-size:.8rem;color:var(--text-muted);margin-bottom:14px;}
</style>
@endsection

@section('content')

{{-- Search & Filter --}}
<form method="GET" action="{{ route('admin.users') }}" class="search-bar">
    <div class="search-input-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="search" class="search-input" placeholder="Search by name, email, or student ID..." value="{{ request('search') }}">
    </div>
    <select name="status" class="filter-select" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
    <button type="submit" class="btn btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        Search
    </button>
    @if(request('search') || request('status'))
        <a href="{{ route('admin.users') }}" class="btn btn-ghost">Clear</a>
    @endif
</form>

@if($users->isNotEmpty())
    <div class="results-info">{{ $users->count() }} user(s) found</div>
@endif

@if($users->isEmpty())
<div style="text-align:center;padding:60px 20px;color:var(--text-muted);">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 14px;display:block;opacity:.2;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    <p>{{ request('search') || request('status') ? 'No users match your search.' : 'No approved users yet.' }}</p>
</div>
@else
<div class="users-grid">
    @foreach($users as $i => $user)
    <div class="user-card" style="animation-delay:{{ $i * 0.04 }}s">
        <div class="user-card-top">
            <div class="user-avatar-big {{ !$user->is_active ? 'inactive' : '' }}">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-email" title="{{ $user->email }}">{{ $user->email }}</div>
            </div>
        </div>
        <div class="user-id-badge">ID: {{ $user->student_id ?? 'N/A' }}</div>
        <div style="margin-bottom:14px;">
            <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="user-card-actions">
            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn {{ $user->is_active ? 'btn-warning' : 'btn-success' }}">
                    @if($user->is_active)
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        Deactivate
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        Activate
                    @endif
                </button>
            </form>
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                  onsubmit="return confirm('Permanently delete {{ addslashes($user->name) }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection