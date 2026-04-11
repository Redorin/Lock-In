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
        border-radius:32px;
        padding:32px;
        position:relative;overflow:hidden;
        box-shadow:var(--shadow-md),var(--inset);
        animation:cardIn .6s var(--ease) both;
        transition:transform var(--t) var(--ease),box-shadow var(--t) var(--ease),border-color var(--t) var(--ease),background var(--t) var(--ease);
    }
    .idc:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg), var(--inset); border-color: var(--accent-border); }
    @keyframes cardIn{from{opacity:0;transform:translateY(24px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}

    /* Decorative blobs */
    .idc::before{content:'';position:absolute;top:-60px;right:-80px;width:240px;height:240px;border-radius:50%;background:radial-gradient(circle,var(--accent-bg) 0%,transparent 70%);pointer-events:none;z-index:0;}
    .idc::after{content:'';position:absolute;bottom:-60px;left:-80px;width:200px;height:200px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.08) 0%,transparent 70%);pointer-events:none;z-index:0;}

    /* Top stripe */
    .id-stripe{
        height:6px;border-radius:99px;
        background:linear-gradient(90deg,var(--accent),#6366f1,var(--accent));
        background-size:200% auto;
        animation:shimmer 3s linear infinite;
        margin:-32px -32px 24px;border-radius:32px 32px 0 0;
        position:relative;z-index:1;
    }
    @keyframes shimmer{0%{background-position:0% center}100%{background-position:200% center}}

    /* Header row */
    .ih{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;position:relative;z-index:1;}
    .isch{font-family:'Plus Jakarta Sans',sans-serif;font-size:.66rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--text-muted);}
    .ilogo{width:36px;height:36px;border-radius:10px;overflow:hidden;border:1px solid var(--accent-border);background:var(--surface2);}
    .ilogo img{width:100%;height:100%;object-fit:cover;display:block;}

    /* Avatar row */
    .iar{display:flex;align-items:center;gap:20px;margin-bottom:24px;position:relative;z-index:1;}
    .iav{
        width:76px;height:76px;border-radius:20px;flex-shrink:0;
        background:linear-gradient(135deg,var(--accent),#6366f1);
        display:flex;align-items:center;justify-content:center;
        font-family:'Plus Jakarta Sans',sans-serif;font-size:2rem;font-weight:800;color:#fff;
        border:2px solid var(--accent-border);
        box-shadow:0 6px 20px var(--accent-glow);
    }
    .inm{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.2rem;font-weight:800;letter-spacing:-.4px;color:var(--text);}
    .irl{font-size:.68rem;color:var(--accent2);font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-top:6px;display:inline-block;background:var(--accent-bg);padding:4px 12px;border-radius:99px;border:1px solid var(--accent-border);}

    /* Divider */
    .idiv{height:1px;background:var(--border);margin:20px 0;position:relative;z-index:1;}

    /* Info grid */
    .ifs{display:grid;grid-template-columns:1fr 1fr;gap:18px;position:relative;z-index:1;}
    .ifl{font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px;}
    .ifv{font-family:'Plus Jakarta Sans',sans-serif;font-size:.9rem;font-weight:700;color:var(--text);}

    /* ID strip */
    .iids{
        margin-top:24px;
        background:var(--surface2);border:1px solid var(--border);
        border-radius:16px;padding:16px 20px;
        display:flex;align-items:center;justify-content:space-between;
        position:relative;z-index:1;
        transition:all var(--t) var(--ease);
    }
    .idc:hover .iids{background:var(--accent-bg);border-color:var(--accent-border);}
    .iidl{font-size:.64rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--accent2);opacity:.8;}
    .iidn{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.25rem;font-weight:800;letter-spacing:.06em;color:var(--text);}

    /* Footer */
    .ift{margin-top:20px;display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1;}
    .ifval{font-size:.68rem;color:var(--text-muted);font-weight:600;}
    .ifsd{display:flex;align-items:center;gap:6px;font-size:.7rem;color:var(--accent2);font-weight:700;}
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