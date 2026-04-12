@extends('admin.layout')
@section('title','Verifications')
@section('page-title','Pending Verifications')
@section('page-sub','Review and approve or reject student registrations')
@section('styles')
<style>
.ith{width:64px;height:44px;object-fit:cover;border-radius:6px;border:1px solid rgba(255,255,255,.1);cursor:pointer;transition:transform .15s;}
.ith:hover{transform:scale(1.05);}
.iph{width:64px;height:44px;border-radius:6px;background:rgba(255,255,255,.04);border:1px dashed rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:.65rem;}
.lb{display:none;position:fixed;inset:0;z-index:600;background:rgba(0,0,0,.9);backdrop-filter:blur(8px);align-items:center;justify-content:center;}
.lb.open{display:flex;}
.lb img{max-width:90vw;max-height:85vh;border-radius:12px;border:1px solid rgba(255,255,255,.1);}
.lbc{position:absolute;top:20px;right:24px;font-size:1.5rem;color:#fff;cursor:pointer;opacity:.7;z-index:601;}
.sg{margin-bottom:20px;}
</style>
@endsection
@section('content')
<div class="gc sg"><div class="gci">
    <div class="ct">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--warn)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Pending Verifications
        @if($pending->count()>0)<span style="background:rgba(251,191,36,.1);color:var(--warn);border:1px solid rgba(251,191,36,.2);font-size:.68rem;font-weight:700;padding:2px 9px;border-radius:99px;">{{ $pending->count() }}</span>@endif
        <div class="ctl"></div>
    </div>
    @if($pending->isEmpty())
        <div class="empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>No pending verifications — all caught up!</div>
    @else
    <div class="table-wrap">
        <table class="dt">
            <thead><tr><th>ID Photo</th><th>Name</th><th>Student ID</th><th>Email</th><th>Registered</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($pending as $u)
                <tr>
                    <td>@if($u->id_image)<img class="ith" src="{{ asset('storage/id_images/'.$u->id_image) }}" onclick="openLb('{{ asset('storage/id_images/'.$u->id_image) }}')" alt="">@else<div class="iph">No img</div>@endif</td>
                    <td style="color:var(--text);font-weight:600;">{{ $u->name }}</td>
                    <td><span style="font-family:monospace;color:var(--accent2);font-size:.85rem;">{{ $u->student_id }}</span></td>
                    <td>{{ $u->email }}</td>
                    <td style="font-size:.78rem;">{{ $u->created_at->format('M d, Y') }}</td>
                    <td><div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <form method="POST" action="{{ route('admin.verifications.approve',$u) }}">@csrf @method('PATCH')
                            <button type="submit" class="btn btn-success"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>Approve</button>
                        </form>
                        <button class="btn btn-danger" onclick="openReject({{ $u->id }},'{{ addslashes($u->name) }}')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>Reject</button>
                    </div></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div></div>

<div class="gc"><div class="gci">
    <div class="ct">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        Rejected Users <div class="ctl"></div>
    </div>
    @if($rejected->isEmpty())
        <div class="empty">No rejected users yet.</div>
    @else
    <div class="table-wrap">
        <table class="dt">
            <thead><tr><th>Name</th><th>Student ID</th><th>Email</th><th>Rejection Reason</th><th>Date</th></tr></thead>
            <tbody>
                @foreach($rejected as $u)
                <tr>
                    <td style="color:var(--text);font-weight:600;">{{ $u->name }}</td>
                    <td><span style="font-family:monospace;color:var(--danger);font-size:.85rem;">{{ $u->student_id }}</span></td>
                    <td>{{ $u->email }}</td>
                    <td style="color:var(--danger);font-size:.82rem;max-width:220px;">{{ $u->rejection_reason ?? '—' }}</td>
                    <td style="font-size:.78rem;">{{ $u->updated_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div></div>
@endsection

@section('modals')
<div class="modal-overlay" id="rejectM"><div class="modal">
    <div style="width:48px;height:48px;border-radius:14px;background:var(--danger-bg);border:1px solid var(--danger-border);display:flex;align-items:center;justify-content:center;margin-bottom:20px;color:var(--danger);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:24px;height:24px;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    </div>
    <div class="modal-title">Reject Registration</div>
    <div class="modal-sub">Rejecting: <strong id="rn" style="color:var(--danger);"></strong></div>
    <form method="POST" id="rejectF">@csrf @method('PATCH')
        <div class="field"><label>Reason for Rejection <span style="color:var(--danger);">*</span></label>
            <textarea name="rejection_reason" placeholder="e.g. Student ID photo is unclear. Please resubmit." required></textarea>
        </div>
        <div class="modal-actions">
            <button type="submit" class="btn btn-danger">Confirm Reject</button>
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('rejectM').classList.remove('open')">Cancel</button>
        </div>
    </form>
</div></div>

<div class="lb" id="lb" onclick="closeLb()">
    <span class="lbc">✕</span>
    <img id="lbi" src="" alt="">
</div>
@endsection

@section('scripts')
<script>
function openReject(id,n){document.getElementById('rn').textContent=n;document.getElementById('rejectF').action='/admin/verifications/'+id+'/reject';document.getElementById('rejectM').classList.add('open');}
function openLb(s){document.getElementById('lbi').src=s;document.getElementById('lb').classList.add('open');}
function closeLb(){document.getElementById('lb').classList.remove('open');}
document.querySelectorAll('.modal-overlay').forEach(o=>o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('open');}));
</script>
@endsection