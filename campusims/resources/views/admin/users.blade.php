@extends('admin.layout')
@section('title','Users')
@section('page-title','Manage Users')
@section('page-sub','All approved student accounts')

@section('styles')
<style>
.ug{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px;}

.uc{
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:22px;
    padding:22px;
    transition:border-color .2s, transform .2s, box-shadow .2s, background .25s;
    box-shadow:0 2px 8px rgba(0,0,0,.06);
}
.uc:hover{
    border-color:var(--accent-border);
    transform:translateY(-2px);
    box-shadow:0 6px 24px rgba(0,0,0,.1);
}

.uct{display:flex;align-items:center;gap:14px;margin-bottom:16px;}

.uav{
    width:50px;height:50px;border-radius:50%;flex-shrink:0;
    background:linear-gradient(135deg,#4f9cf9,#1a6fe8);
    display:flex;align-items:center;justify-content:center;
    font-size:1.1rem;font-weight:800;color:#fff;
    border:2px solid var(--accent-border);
    box-shadow:0 2px 8px var(--accent-glow);
}
.uav.off{
    background:var(--surface2);
    filter:grayscale(1);
    border-color:var(--border2);
    box-shadow:none;
}
.uav.off span{ color:var(--text-muted); }

.un{font-size:.95rem;font-weight:700;color:var(--text);}
.ue{font-size:.75rem;color:var(--text-muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;}

.uid{
    display:inline-block;
    background:var(--accent-bg);
    border:1px solid var(--accent-border);
    color:var(--accent);
    font-size:.72rem;font-weight:600;letter-spacing:.04em;
    padding:4px 10px;border-radius:99px;margin-bottom:14px;
}

.uca{display:flex;gap:8px;}
.uca .btn{flex:1;justify-content:center;font-size:.78rem;padding:7px 10px;}
.ri{font-size:.8rem;color:var(--text-muted);margin-bottom:14px;}
</style>
@endsection

@section('content')
<form method="GET" action="{{ route('admin.users') }}" class="search-bar">
    <div class="siw">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="search" class="si-inp" placeholder="Search by name, email, or student ID..." value="{{ request('search') }}">
    </div>
    <select name="status" class="fsel" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="active"   {{ request('status')=='active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
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
    <div class="ri">{{ $users->count() }} user(s) found</div>
@endif

@if($users->isEmpty())
    <div style="text-align:center;padding:60px;color:var(--text-muted);">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
             style="margin:0 auto 14px;display:block;opacity:.2;">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
        </svg>
        <p>{{ request('search') || request('status') ? 'No users match your search.' : 'No approved users yet.' }}</p>
    </div>
@else
    <div class="ug">
        @foreach($users as $i => $u)
        <div class="uc">
            <div class="uct">
                <div class="uav {{ !$u->is_active ? 'off' : '' }}">
                    <span>{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                </div>
                <div style="min-width:0;">
                    <div class="un">{{ $u->name }}</div>
                    <div class="ue" title="{{ $u->email }}">{{ $u->email }}</div>
                </div>
            </div>

            <div class="uid">ID: {{ $u->student_id ?? 'N/A' }}</div>

            <div style="margin-bottom:14px;">
                <span class="sbadge {{ $u->is_active ? 'sa' : 'si' }}">
                    {{ $u->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="uca">
                <form method="POST" action="{{ route('admin.users.toggle', $u) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn {{ $u->is_active ? 'btn-warn' : 'btn-success' }}">
                        @if($u->is_active)
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            Deactivate
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            Activate
                        @endif
                    </button>
                </form>
                <form method="POST" action="javascript:void(0)"
                      onsubmit="delConfirm('{{ route('admin.users.destroy', $u) }}', 'Permanently delete {{ addslashes($u->name) }}?')">
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