@extends('admin.layout')
@section('title','Verifications')
@section('page-title','Pending Verifications')
@section('page-sub','Review and approve or reject student registrations')

@section('styles')
<style>
    .section-gap { margin-bottom: 20px; }
    .id-thumb {
        width: 64px; height: 44px; object-fit: cover;
        border-radius: 6px; border: 1px solid var(--glass-border);
        cursor: pointer; transition: transform .15s;
    }
    .id-thumb:hover { transform: scale(1.05); }
    .id-placeholder {
        width: 64px; height: 44px; border-radius: 6px;
        background: rgba(255,255,255,.05); border: 1px dashed var(--glass-border);
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); font-size: .65rem;
    }
    /* image lightbox */
    .lightbox { display:none; position:fixed; inset:0; z-index:600; background:rgba(0,0,0,.85); backdrop-filter:blur(8px); align-items:center; justify-content:center; }
    .lightbox.open { display:flex; }
    .lightbox img { max-width:90vw; max-height:85vh; border-radius:12px; border:1px solid var(--glass-border); }
    .lightbox-close { position:absolute; top:20px; right:24px; font-size:1.5rem; color:#fff; cursor:pointer; opacity:.7; }
    .lightbox-close:hover { opacity:1; }
</style>
@endsection

@section('content')

{{-- ── Pending ── --}}
<div class="glass-card section-gap">
    <div class="glass-card-inner">
        <div class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent3)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Pending Student Verifications
            @if($pending->count() > 0)
                <span style="background:rgba(245,158,11,.15);color:var(--accent3);border:1px solid rgba(245,158,11,.2);font-size:.68rem;font-weight:700;padding:2px 9px;border-radius:99px;">{{ $pending->count() }}</span>
            @endif
            <div class="card-title-line"></div>
        </div>

        @if($pending->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                No pending verifications — all caught up!
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID Photo</th>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending as $user)
                    <tr>
                        <td>
                            @if($user->id_image)
                                <img class="id-thumb" src="{{ asset('storage/id_images/' . $user->id_image) }}"
                                     onclick="openLightbox('{{ asset('storage/id_images/' . $user->id_image) }}')"
                                     alt="Student ID">
                            @else
                                <div class="id-placeholder">No img</div>
                            @endif
                        </td>
                        <td style="color:var(--text);font-weight:600;">{{ $user->name }}</td>
                        <td><span style="font-family:monospace;color:var(--accent);font-size:.85rem;">{{ $user->student_id }}</span></td>
                        <td>{{ $user->email }}</td>
                        <td style="font-size:.78rem;">{{ $user->created_at->format('M d, Y · g:i A') }}</td>
                        <td>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                {{-- Approve --}}
                                <form method="POST" action="{{ route('admin.verifications.approve', $user) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-success">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        Approve
                                    </button>
                                </form>
                                {{-- Reject --}}
                                <button class="btn btn-danger"
                                    onclick="openRejectModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- ── Rejected ── --}}
<div class="glass-card">
    <div class="glass-card-inner">
        <div class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Rejected Users
            <div class="card-title-line"></div>
        </div>

        @if($rejected->isEmpty())
            <div class="empty-state">No rejected users yet.</div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Email</th>
                        <th>Rejection Reason</th>
                        <th>Rejected At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rejected as $user)
                    <tr>
                        <td style="color:var(--text);font-weight:600;">{{ $user->name }}</td>
                        <td><span style="font-family:monospace;color:var(--danger);font-size:.85rem;">{{ $user->student_id }}</span></td>
                        <td>{{ $user->email }}</td>
                        <td style="color:var(--danger);font-size:.82rem;max-width:260px;">{{ $user->rejection_reason ?? '—' }}</td>
                        <td style="font-size:.78rem;">{{ $user->updated_at->format('M d, Y · g:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <div class="modal-title">Reject Registration</div>
        <div class="modal-sub">You are rejecting: <strong id="rejectUserName" style="color:var(--danger);"></strong></div>
        <form method="POST" id="rejectForm">
            @csrf @method('PATCH')
            <div class="field">
                <label>Reason for Rejection <span style="color:var(--danger);">*</span></label>
                <textarea name="rejection_reason" placeholder="e.g. Student ID photo is unclear. Please resubmit with a clearer photo." required></textarea>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn btn-danger" style="flex:1;">Confirm Reject</button>
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('rejectModal').classList.remove('open')">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Image Lightbox --}}
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <span class="lightbox-close">✕</span>
    <img id="lightboxImg" src="" alt="Student ID">
</div>

@endsection

@section('scripts')
<script>
function openRejectModal(userId, userName) {
    document.getElementById('rejectUserName').textContent = userName;
    document.getElementById('rejectForm').action = '/admin/verifications/' + userId + '/reject';
    document.getElementById('rejectModal').classList.add('open');
}
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('open');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
}
document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if(e.target===o) o.classList.remove('open'); });
});
</script>
@endsection