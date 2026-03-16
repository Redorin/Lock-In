@extends('student.layout')
@section('title','My ID Card')
@section('page-title','My ID Card')
@section('page-sub','Your digital student identification card')

@section('styles')
<style>
    .idwrap{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:340px;gap:20px;padding:10px 0;}
    .idc{width:100%;max-width:380px;background:linear-gradient(135deg,#060f22 0%,#04080f 55%,#0a0818 100%);border:1px solid rgba(79,156,249,.2);border-radius:20px;padding:24px;position:relative;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.6),inset 0 1px 0 rgba(255,255,255,.08);animation:ci .6s cubic-bezier(.22,1,.36,1) both;}
    @keyframes ci{from{opacity:0;transform:translateY(20px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}
    .idc::before{content:'';position:absolute;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(79,156,249,.08) 0%,transparent 70%);top:-70px;right:-60px;pointer-events:none;}
    .idc::after{content:'';position:absolute;width:160px;height:160px;border-radius:50%;background:radial-gradient(circle,rgba(167,139,250,.07) 0%,transparent 70%);bottom:-50px;left:-40px;pointer-events:none;}
    .ih{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
    .isch{font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--accent2);}
    .ilogo{width:30px;height:30px;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);border-radius:8px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 12px rgba(79,156,249,.3);}
    .ilogo svg{width:15px;height:15px;}
    .iar{display:flex;align-items:center;gap:16px;margin-bottom:18px;}
    .iav{width:64px;height:64px;border-radius:13px;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;color:#fff;border:2px solid rgba(79,156,249,.35);flex-shrink:0;box-shadow:0 0 20px rgba(79,156,249,.2);}
    .inm{font-size:1rem;font-weight:800;letter-spacing:-.3px;color:#fff;}
    .irl{font-size:.68rem;color:var(--accent2);font-weight:600;letter-spacing:.08em;text-transform:uppercase;margin-top:3px;}
    .idiv{height:1px;background:rgba(255,255,255,.07);margin:14px 0;}
    .ifs{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
    .ifl{font-size:.58rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.25);margin-bottom:3px;}
    .ifv{font-size:.82rem;font-weight:600;color:rgba(255,255,255,.85);}
    .iids{margin-top:16px;background:rgba(79,156,249,.06);border:1px solid rgba(79,156,249,.15);border-radius:10px;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;}
    .iidl{font-size:.58rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(79,156,249,.6);}
    .iidn{font-size:1rem;font-weight:800;letter-spacing:.06em;color:var(--accent2);font-family:monospace;}
    .ift{margin-top:14px;display:flex;align-items:center;justify-content:space-between;}
    .ifval{font-size:.6rem;color:rgba(255,255,255,.2);}
    .ifsd{display:flex;align-items:center;gap:6px;font-size:.65rem;color:var(--accent2);font-weight:600;}
    .pd{width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pp 2s infinite;}
    @keyframes pp{0%{box-shadow:0 0 0 0 rgba(79,156,249,.5)}70%{box-shadow:0 0 0 6px rgba(79,156,249,0)}100%{box-shadow:0 0 0 0 rgba(79,156,249,0)}}
    .hint{font-size:.75rem;color:var(--muted);text-align:center;display:flex;align-items:center;gap:8px;justify-content:center;}
    @media print{.sidebar,.topbar,.hint,.mob-topbar{display:none!important}.idc{box-shadow:none}}
</style>
@endsection

@section('content')
<div class="idwrap">
    <div class="idc">
        <div class="ih">
            <div class="isch">CampuSIMS · Student</div>
            <div class="ilogo"><svg viewBox="0 0 20 20" fill="none"><path d="M10 2L3 6v8l7 4 7-4V6L10 2z" fill="#fff" fill-opacity=".9"/><path d="M10 2v12M3 6l7 4 7-4" stroke="#fff" stroke-opacity=".5" stroke-width="1.2"/></svg></div>
        </div>
        <div class="iar">
            <div class="iav">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div><div class="inm">{{ $user->name }}</div><div class="irl">Student</div></div>
        </div>
        <div class="idiv"></div>
        <div class="ifs">
            <div><div class="ifl">Email</div><div class="ifv" style="font-size:.72rem;word-break:break-all;">{{ $user->email }}</div></div>
            <div><div class="ifl">Status</div><div class="ifv" style="color:var(--accent2);">{{ ucfirst($user->status) }}</div></div>
            <div><div class="ifl">Account</div><div class="ifv" style="color:{{ $user->is_active ? 'var(--accent2)' : 'var(--danger)' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</div></div>
            <div><div class="ifl">Since</div><div class="ifv" style="font-size:.8rem;">{{ $user->created_at->format('M Y') }}</div></div>
        </div>
        <div class="iids">
            <div class="iidl">Student ID</div>
            <div class="iidn">{{ $user->student_id ?? 'NOT SET' }}</div>
        </div>
        <div class="ift">
            <div class="ifval">Valid · {{ date('Y') }}–{{ date('Y') + 1 }}</div>
            <div class="ifsd"><span class="pd"></span> Verified</div>
        </div>
    </div>
    <div class="hint">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        Ctrl+P / Cmd+P to print or save as PDF
    </div>
</div>
@endsection