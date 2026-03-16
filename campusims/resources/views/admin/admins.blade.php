@extends('admin.layout')
@section('title','Manage Admins')
@section('page-title','Manage Admins')
@section('page-sub','Create and manage administrator accounts')
@section('content')
<div style="display:flex;justify-content:flex-end;margin-bottom:16px;">
    <button class="btn btn-blue" onclick="document.getElementById('addAdminM').classList.add('open')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Create Admin Account
    </button>
</div>
<div class="gc"><div class="gci">
    <div class="ct">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent2)" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        All Administrators <div class="ctl"></div>
    </div>
    <div class="table-wrap">
        <table class="dt">
            <thead><tr><th>Name</th><th>Email</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($admins as $a)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:800;color:#fff;flex-shrink:0;">{{ strtoupper(substr($a->name,0,1)) }}</div>
                            <div>
                                <div style="font-weight:600;color:var(--white);font-size:.9rem;">{{ $a->name }}</div>
                                @if($a->id===auth()->id())<div style="font-size:.68rem;color:var(--accent2);font-weight:600;">You</div>@endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $a->email }}</td>
                    <td style="font-size:.78rem;">{{ $a->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($a->id!==auth()->id())
                        <form method="POST" action="{{ route('admin.admins.destroy',$a) }}" onsubmit="return confirm('Delete admin {{ addslashes($a->name) }}?')">@csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>Delete</button>
                        </form>
                        @else<span style="font-size:.75rem;color:var(--muted);">Current session</span>@endif
                    </td>
                </tr>
                @empty<tr><td colspan="4"><div class="empty">No admins found.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div></div>

<div class="modal-overlay" id="addAdminM"><div class="modal">
    <div class="modal-title">Create Admin Account</div>
    <div class="modal-sub">This account will have full admin access to CampuSIMS.</div>
    <form method="POST" action="{{ route('admin.admins.store') }}">@csrf
        <div class="field"><label>Full Name</label><input type="text" name="name" placeholder="Admin Staff" required></div>
        <div class="field"><label>Email Address</label><input type="email" name="email" placeholder="admin@school.edu" required></div>
        <div class="field"><label>Password</label><input type="password" name="password" placeholder="Min. 8 characters" required></div>
        <div class="field"><label>Confirm Password</label><input type="password" name="password_confirmation" placeholder="Repeat password" required></div>
        <div class="modal-actions">
            <button type="submit" class="btn btn-blue">Create Admin</button>
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('addAdminM').classList.remove('open')">Cancel</button>
        </div>
    </form>
</div></div>
@endsection
@section('scripts')
<script>document.querySelectorAll('.modal-overlay').forEach(o=>o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('open');}));</script>
@endsection