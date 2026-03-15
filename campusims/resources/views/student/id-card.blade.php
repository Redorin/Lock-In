@extends('student.layout')
@section('title','My ID Card')
@section('page-title','My ID Card')
@section('page-sub','Your digital student identification card')

@section('styles')
<style>
    .id-card-wrap { display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:400px;gap:24px; }
    .id-card {
        width:380px;
        background:linear-gradient(135deg,#0d2318 0%,#091510 60%,#0d1a2e 100%);
        border:1px solid rgba(0,229,160,.2);border-radius:20px;padding:28px;
        position:relative;overflow:hidden;
        box-shadow:0 20px 60px rgba(0,0,0,.5),0 0 0 1px rgba(0,229,160,.1);
        animation:cardIn .6s cubic-bezier(.22,1,.36,1) both;
    }
    @keyframes cardIn{from{opacity:0;transform:translateY(20px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}
    .id-card::before{content:'';position:absolute;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(0,229,160,.08) 0%,transparent 70%);top:-60px;right:-60px;pointer-events:none;}
    .id-card::after{content:'';position:absolute;width:150px;height:150px;border-radius:50%;background:radial-gradient(circle,rgba(124,111,247,.08) 0%,transparent 70%);bottom:-40px;left:-40px;pointer-events:none;}
    .card-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
    .card-school{font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--accent);}
    .card-logo{width:32px;height:32px;background:linear-gradient(135deg,var(--accent),#00b87a);border-radius:8px;display:flex;align-items:center;justify-content:center;}
    .card-logo svg{width:16px;height:16px;}
    .card-avatar-row{display:flex;align-items:center;gap:18px;margin-bottom:20px;}
    .card-avatar{width:72px;height:72px;border-radius:14px;background:linear-gradient(135deg,#7c6ff7,#5147c9);display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:800;color:#fff;border:2px solid rgba(124,111,247,.3);flex-shrink:0;}
    .card-name{font-size:1.1rem;font-weight:800;letter-spacing:-.3px;color:#fff;}
    .card-role{font-size:.72rem;color:var(--accent);font-weight:600;letter-spacing:.08em;text-transform:uppercase;margin-top:3px;}
    .card-divider{height:1px;background:rgba(255,255,255,.08);margin:16px 0;}
    .card-fields{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .card-field-label{font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(221,238,230,.3);margin-bottom:4px;}
    .card-field-value{font-size:.88rem;font-weight:600;color:#ddeee6;}
    .card-id-strip{margin-top:20px;background:rgba(0,229,160,.06);border:1px solid rgba(0,229,160,.15);border-radius:10px;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;}
    .card-id-label{font-size:.62rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(0,229,160,.6);}
    .card-id-number{font-size:1.1rem;font-weight:800;letter-spacing:.08em;color:var(--accent);font-family:monospace;}
    .card-footer{margin-top:16px;display:flex;align-items:center;justify-content:space-between;}
    .card-validity{font-size:.65rem;color:rgba(221,238,230,.25);}
    .card-status-dot{display:flex;align-items:center;gap:6px;font-size:.68rem;color:var(--accent);font-weight:600;}
    .pulse-dot{width:7px;height:7px;border-radius:50%;background:var(--accent);box-shadow:0 0 0 0 rgba(0,229,160,.5);animation:pulse 2s infinite;}
    @keyframes pulse{0%{box-shadow:0 0 0 0 rgba(0,229,160,.5)}70%{box-shadow:0 0 0 6px rgba(0,229,160,0)}100%{box-shadow:0 0 0 0 rgba(0,229,160,0)}}
    .card-hint{font-size:.78rem;color:var(--text-muted);text-align:center;display:flex;align-items:center;gap:8px;justify-content:center;}
    .card-hint svg{width:14px;height:14px;}
    @media print{.sidebar,.topbar,.card-hint{display:none!important}.id-card{box-shadow:none;border:1px solid #ccc}}
</style>
@endsection

@section('content')
<div class="id-card-wrap">
    <div class="id-card">
        <div class="card-header">
            <div class="card-school">CampuSIMS · Student Portal</div>
            <div class="card-logo">
                <svg viewBox="0 0 20 20" fill="none"><path d="M10 2L3 6v8l7 4 7-4V6L10 2z" fill="#091510"/><path d="M10 2v12M3 6l7 4 7-4" stroke="#091510" stroke-width="1.5"/></svg>
            </div>
        </div>
        <div class="card-avatar-row">
            <div class="card-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <div class="card-name">{{ $user->name }}</div>
                <div class="card-role">Student</div>
            </div>
        </div>
        <div class="card-divider"></div>
        <div class="card-fields">
            <div><div class="card-field-label">Email</div><div class="card-field-value" style="font-size:.78rem;word-break:break-all;">{{ $user->email }}</div></div>
            <div><div class="card-field-label">Status</div><div class="card-field-value" style="color:var(--accent);">{{ ucfirst($user->status) }}</div></div>
            <div><div class="card-field-label">Account</div><div class="card-field-value" style="color:{{ $user->is_active ? 'var(--accent)' : 'var(--danger)' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</div></div>
            <div><div class="card-field-label">Member Since</div><div class="card-field-value" style="font-size:.82rem;">{{ $user->created_at->format('M Y') }}</div></div>
        </div>
        <div class="card-id-strip">
            <div class="card-id-label">Student ID</div>
            <div class="card-id-number">{{ $user->student_id ?? 'NOT SET' }}</div>
        </div>
        <div class="card-footer">
            <div class="card-validity">Valid · Academic Year {{ date('Y') }}–{{ date('Y') + 1 }}</div>
            <div class="card-status-dot"><span class="pulse-dot"></span> Verified</div>
        </div>
    </div>
    <div class="card-hint">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        Use Ctrl+P / Cmd+P to print or save as PDF
    </div>
</div>
@endsection