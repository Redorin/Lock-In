@extends('admin.layout')
@section('title','Spaces')
@section('page-title','Manage Spaces')
@section('page-sub','Add, edit, or remove campus spaces')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
    <form method="GET" action="{{ route('admin.spaces') }}" style="display:flex;gap:8px;flex:1;flex-wrap:wrap;">
        <div class="siw"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" class="si-inp" placeholder="Search spaces..." value="{{ request('search') }}">
        </div>
        <select name="building" class="fsel" onchange="this.form.submit()">
            <option value="">All Buildings</option>
            @foreach($buildings as $b)<option value="{{ $b }}" {{ request('building')==$b?'selected':'' }}>{{ $b }}</option>@endforeach
        </select>
        @if(request('search')||request('building'))<a href="{{ route('admin.spaces') }}" class="btn btn-ghost">Clear</a>@endif
    </form>
    <button class="btn btn-blue" onclick="document.getElementById('addM').classList.add('open')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Add Space
    </button>
</div>
<div class="gc"><div class="gci">
    <div class="ct">All Spaces <span style="font-size:.75rem;font-weight:500;color:var(--muted);">{{ $spaces->count() }} result(s)</span><div class="ctl"></div></div>
    <div class="table-wrap">
        <table class="dt">
            <thead><tr><th>Building</th><th>Space Name</th><th>Capacity</th><th>Occupancy</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($spaces as $s)
                <tr>
                    <td>{{ $s->building }}</td>
                    <td style="color:var(--white);font-weight:500;">{{ $s->name }}</td>
                    <td>{{ $s->capacity }}</td>
                    <td>{{ $s->current_occupancy }}</td>
                    <td><span class="sbadge {{ in_array($s->status,['LOW','MODERATE'])?'sa':'si' }}">{{ $s->status }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            <button class="btn btn-ghost" onclick="openEdit({{ $s->id }},'{{ addslashes($s->building) }}','{{ addslashes($s->name) }}',{{ $s->capacity }},{{ $s->current_occupancy }})">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit
                            </button>
                            <form method="POST" action="{{ route('admin.spaces.destroy',$s) }}" onsubmit="return confirm('Delete {{ addslashes($s->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty<tr><td colspan="6"><div class="empty">No spaces found.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div></div>

<div class="modal-overlay" id="addM"><div class="modal">
    <div class="modal-title">Add New Space</div>
    <div class="modal-sub">Fill in the details for the new campus space.</div>
    <form method="POST" action="{{ route('admin.spaces.store') }}">@csrf
        <div class="field"><label>Building Name</label><input type="text" name="building" placeholder="e.g. V Building" required></div>
        <div class="field"><label>Space Name</label><input type="text" name="name" placeholder="e.g. Canteen" required></div>
        <div class="field"><label>Maximum Capacity</label><input type="number" name="capacity" min="1" placeholder="100" required></div>
        <div class="modal-actions">
            <button type="submit" class="btn btn-blue">Add Space</button>
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('addM').classList.remove('open')">Cancel</button>
        </div>
    </form>
</div></div>

<div class="modal-overlay" id="editM"><div class="modal">
    <div class="modal-title">Edit Space</div>
    <div class="modal-sub" id="editLbl">Update capacity and occupancy.</div>
    <form method="POST" id="editF">@csrf @method('PATCH')
        <div class="field"><label>Maximum Capacity</label><input type="number" name="capacity" id="ec" min="1" required></div>
        <div class="field"><label>Current Occupancy</label><input type="number" name="current_occupancy" id="eo" min="0" required></div>
        <div class="modal-actions">
            <button type="submit" class="btn btn-blue">Save Changes</button>
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('editM').classList.remove('open')">Cancel</button>
        </div>
    </form>
</div></div>
@endsection
@section('scripts')
<script>
function openEdit(id,b,n,c,o){document.getElementById('editLbl').textContent=b+' — '+n;document.getElementById('ec').value=c;document.getElementById('eo').value=o;document.getElementById('editF').action='/admin/spaces/'+id;document.getElementById('editM').classList.add('open');}
document.querySelectorAll('.modal-overlay').forEach(o=>o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('open');}));
</script>
@endsection