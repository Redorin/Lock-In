@extends('student.layout')
@section('title','My ID Card')
@section('page-title','My ID Card')
@section('page-sub','Your digital student identification card')

@section('styles')
<style>
    .idwrap{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:380px;gap:20px;padding:16px 0;}

    .idc {
        width:100%;max-width:400px;
        background:var(--surface);
        border:1px solid var(--border);
        border-radius:24px;
        padding:28px;
        position:relative;overflow:hidden;
        box-shadow:var(--shadow-md),var(--inset);
        animation:cardIn .6s var(--ease) both;
        transition:background var(--t) var(--ease),border-color var(--t) var(--ease);
    }
    @keyframes cardIn{from{opacity:0;transform:translateY(20px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}

    /* Decorative blobs */
    .idc::before{content:'';position:absolute;top:-60px;right:-60px;width:200px;height:200px;border-radius:50%;background:radial-gradient(circle,var(--accent-bg) 0%,transparent 70%);pointer-events:none;}
    .idc::after{content:'';position:absolute;bottom:-40px;left:-40px;width:150px;height:150px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.06) 0%,transparent 70%);pointer-events:none;}

    /* Top stripe */
    .id-stripe{
        height:4px;border-radius:99px;
        background:linear-gradient(90deg,var(--accent),#6366f1,var(--accent));
        background-size:200% auto;
        animation:shimmer 3s linear infinite;
        margin:-28px -28px 22px;border-radius:24px 24px 0 0;
    }
    @keyframes shimmer{0%{background-position:0% center}100%{background-position:200% center}}

    /* Header row */
    .ih{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;position:relative;z-index:1;}
    .isch{font-family:'Plus Jakarta Sans',sans-serif;font-size:.64rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--text-muted);}
    .ilogo{width:30px;height:30px;border-radius:8px;overflow:hidden;border:1px solid var(--accent-border);}
    .ilogo img{width:100%;height:100%;object-fit:cover;display:block;}

    /* Avatar row */
    .iar{display:flex;align-items:center;gap:18px;margin-bottom:20px;position:relative;z-index:1;}
    .iav{
        width:68px;height:68px;border-radius:16px;flex-shrink:0;
        background:linear-gradient(135deg,var(--accent),#6366f1);
        display:flex;align-items:center;justify-content:center;
        font-family:'Plus Jakarta Sans',sans-serif;font-size:1.7rem;font-weight:800;color:#fff;
        border:2px solid var(--accent-border);
        box-shadow:0 4px 20px var(--accent-glow);
    }
    .inm{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.05rem;font-weight:800;letter-spacing:-.3px;color:var(--text);}
    .irl{font-size:.7rem;color:var(--accent2);font-weight:600;letter-spacing:.08em;text-transform:uppercase;margin-top:4px;}

    /* Divider */
    .idiv{height:1px;background:var(--border);margin:16px 0;position:relative;z-index:1;}

    /* Info grid */
    .ifs{display:grid;grid-template-columns:1fr 1fr;gap:16px;position:relative;z-index:1;}
    .ifl{font-size:.6rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);margin-bottom:3px;}
    .ifv{font-size:.85rem;font-weight:600;color:var(--text);}

    /* ID strip */
    .iids{
        margin-top:18px;
        background:var(--accent-bg);border:1px solid var(--accent-border);
        border-radius:12px;padding:14px 18px;
        display:flex;align-items:center;justify-content:space-between;
        position:relative;z-index:1;
    }
    .iidl{font-size:.6rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--accent2);opacity:.7;}
    .iidn{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.1rem;font-weight:800;letter-spacing:.06em;color:var(--accent2);}

    /* Footer */
    .ift{margin-top:16px;display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1;}
    .ifval{font-size:.62rem;color:var(--text-muted);}
    .ifsd{display:flex;align-items:center;gap:6px;font-size:.66rem;color:var(--accent2);font-weight:600;}
    .pd{width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pp 2.5s ease infinite;}
    @keyframes pp{0%{box-shadow:0 0 0 0 var(--accent-glow)}60%{box-shadow:0 0 0 6px transparent}100%{box-shadow:0 0 0 0 transparent}}
</style>
@endsection

@section('content')
<div class="idwrap">
    <div class="idc">
        <div class="id-stripe"></div>
        <div class="ih">
            <div class="isch">UDDSafeSpaces · Student</div>
            <div class="ilogo"><img src="{{ asset('storage/udd-logo.jpg') }}" alt="UDD" onerror="this.style.display='none'"></div>
        </div>
        <div class="iar">
            <div class="iav">{{ strtoupper(substr($user->name,0,1)) }}</div>
            <div><div class="inm">{{ $user->name }}</div><div class="irl">Verified Student</div></div>
        </div>
        <div class="idiv"></div>
        <div class="ifs">
            <div><div class="ifl">Email</div><div class="ifv" style="font-size:.76rem;word-break:break-all;">{{ $user->email }}</div></div>
            <div><div class="ifl">Status</div><div class="ifv" style="color:var(--accent2);">{{ ucfirst($user->status) }}</div></div>
            <div><div class="ifl">Account</div><div class="ifv" style="color:{{ $user->is_active ? 'var(--accent2)' : 'var(--danger)' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</div></div>
            <div><div class="ifl">Member Since</div><div class="ifv" style="font-size:.82rem;">{{ $user->created_at->format('M Y') }}</div></div>
        </div>
        <div class="iids">
            <div class="iidl">Student ID</div>
            <div class="iidn">{{ $user->student_id ?? 'NOT SET' }}</div>
        </div>
        <div class="ift">
            <div class="ifval">Valid · Academic Year {{ date('Y') }}–{{ date('Y')+1 }}</div>
            <div class="ifsd"><span class="pd"></span>Verified</div>
        </div>
    </div>
</div>
@endsection