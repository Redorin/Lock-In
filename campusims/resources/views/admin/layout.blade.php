<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CampuSIMS Admin — @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --bg:#04080f;--gh:rgba(255,255,255,.08);--gb:rgba(255,255,255,.10);
            --glass:rgba(255,255,255,.04);--gbd:rgba(255,255,255,.08);
            --accent:#4f9cf9;--accent2:#7eb8ff;--accent3:#a78bfa;--warn:#fbbf24;
            --white:rgba(255,255,255,.92);--soft:rgba(255,255,255,.55);--muted:rgba(255,255,255,.28);
            --danger:#f87171;--sw:72px;--rl:22px;--rm:14px;--rs:10px;
        }
        html{background:var(--bg);}
        body{font-family:'Outfit',sans-serif;background:var(--bg);color:var(--white);-webkit-font-smoothing:antialiased;margin:0;padding:0;}
        body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
            background:radial-gradient(ellipse 70% 55% at 5% 5%,rgba(30,80,200,.2) 0%,transparent 55%),
                radial-gradient(ellipse 50% 50% at 90% 90%,rgba(100,60,220,.12) 0%,transparent 55%),
                radial-gradient(ellipse 35% 35% at 55% 15%,rgba(79,156,249,.05) 0%,transparent 50%);}

        /* ════════════ DESKTOP (>900px) ════════════ */
        .desktop-sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sw);z-index:100;background:var(--glass);border-right:1px solid var(--gbd);backdrop-filter:blur(28px);display:flex;flex-direction:column;align-items:center;padding:18px 0;box-shadow:inset 0 1px 0 rgba(255,255,255,.07);}
        .s-logo{width:42px;height:42px;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);border-radius:13px;display:flex;align-items:center;justify-content:center;margin-bottom:28px;text-decoration:none;box-shadow:0 4px 20px rgba(79,156,249,.35);flex-shrink:0;}
        .s-logo svg{width:20px;height:20px;}
        .s-nav{display:flex;flex-direction:column;align-items:center;gap:4px;flex:1;width:100%;padding:0 10px;}
        .ni{width:48px;height:48px;border-radius:var(--rs);display:flex;align-items:center;justify-content:center;text-decoration:none;color:var(--muted);position:relative;transition:background .18s,color .18s;}
        .ni svg{width:20px;height:20px;transition:transform .18s;}
        .ni:hover{background:var(--gh);color:var(--soft);}
        .ni:hover svg{transform:scale(1.1);}
        .ni.active{background:rgba(79,156,249,.15);color:var(--accent2);}
        .ni.active::before{content:'';position:absolute;left:-10px;width:3px;height:22px;background:var(--accent);border-radius:0 3px 3px 0;box-shadow:0 0 12px var(--accent);}
        .ni .tip{position:absolute;left:58px;background:rgba(4,8,15,.96);border:1px solid rgba(255,255,255,.1);color:var(--white);font-size:.73rem;font-weight:500;padding:5px 10px;border-radius:8px;white-space:nowrap;opacity:0;pointer-events:none;transform:translateX(-6px);transition:opacity .15s,transform .15s;z-index:999;}
        .ni:hover .tip{opacity:1;transform:translateX(0);}
        .nbadge{position:absolute;top:6px;right:6px;width:16px;height:16px;border-radius:50%;background:var(--warn);color:#1a0f00;font-size:.6rem;font-weight:800;display:flex;align-items:center;justify-content:center;}
        .s-bot{display:flex;flex-direction:column;align-items:center;gap:10px;padding:0 10px;}
        .s-av{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:700;color:#fff;border:2px solid rgba(79,156,249,.4);}
        .s-lo{width:38px;height:38px;border-radius:var(--rs);background:transparent;border:1px solid rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:all .18s;}
        .s-lo svg{width:16px;height:16px;}
        .s-lo:hover{background:rgba(248,113,113,.1);color:var(--danger);border-color:rgba(248,113,113,.25);}

        .desktop-main{margin-left:var(--sw);min-height:100vh;position:relative;z-index:1;}
        .desktop-topbar{display:flex;align-items:center;justify-content:space-between;padding:20px 28px 0;flex-wrap:wrap;gap:12px;}
        .topbar-title h1{font-size:1.65rem;font-weight:800;letter-spacing:-.5px;}
        .topbar-title p{font-size:.83rem;color:var(--soft);margin-top:3px;}
        .abadge{display:flex;align-items:center;gap:8px;background:rgba(79,156,249,.08);border:1px solid rgba(79,156,249,.15);border-radius:99px;padding:7px 16px 7px 10px;font-size:.82rem;color:var(--accent2);}
        .abadge svg{width:14px;height:14px;}
        .desktop-content{padding:20px 28px 40px;}

        /* ════════════ MOBILE (<900px) ════════════ */
        .mobile-topbar{display:none;align-items:center;justify-content:space-between;padding:14px 18px;background:rgba(4,8,15,.97);border-bottom:1px solid rgba(255,255,255,.07);position:sticky;top:0;z-index:50;backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);}
        .mob-brand{display:flex;align-items:center;gap:10px;text-decoration:none;}
        .mob-icon{width:34px;height:34px;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);border-radius:9px;display:flex;align-items:center;justify-content:center;}
        .mob-icon svg{width:16px;height:16px;}
        .mob-name{font-size:1rem;font-weight:800;letter-spacing:-.3px;color:var(--white);}
        .mob-name span{color:var(--accent2);}
        .mob-right{display:flex;align-items:center;gap:8px;}
        .mob-pending{background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.2);color:var(--warn);font-size:.7rem;font-weight:700;padding:4px 10px;border-radius:99px;}
        .ham{width:38px;height:38px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:9px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--soft);}
        .ham svg{width:20px;height:20px;}
        .mobile-content{display:none;padding:16px 16px 40px;position:relative;z-index:1;}
        .mob-page-header{margin-bottom:16px;}
        .mob-page-header h2{font-size:1.3rem;font-weight:800;letter-spacing:-.4px;}
        .mob-page-header p{font-size:.78rem;color:var(--soft);margin-top:2px;}

        /* ════════════ DRAWER ════════════ */
        .drawer-overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);}
        .drawer-overlay.open{display:block;}
        .drawer{position:fixed;top:0;left:-290px;bottom:0;width:290px;z-index:201;background:#060d1a;border-right:1px solid rgba(255,255,255,.1);transition:left .28s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;overflow:hidden;}
        .drawer.open{left:0;}
        .d-head{display:flex;align-items:center;justify-content:space-between;padding:20px;border-bottom:1px solid rgba(255,255,255,.07);}
        .d-brand{display:flex;align-items:center;gap:10px;}
        .d-brand-icon{width:36px;height:36px;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);border-radius:10px;display:flex;align-items:center;justify-content:center;}
        .d-brand-icon svg{width:18px;height:18px;}
        .d-brand-name{font-size:1rem;font-weight:800;letter-spacing:-.3px;}
        .d-brand-name span{color:var(--accent2);}
        .d-close{width:32px;height:32px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--soft);}
        .d-close svg{width:16px;height:16px;}
        .d-user{display:flex;align-items:center;gap:12px;padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.07);}
        .d-av{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:800;color:#fff;border:2px solid rgba(79,156,249,.4);flex-shrink:0;}
        .d-uname{font-size:.92rem;font-weight:600;}
        .d-urole{font-size:.7rem;color:var(--accent2);font-weight:600;text-transform:uppercase;letter-spacing:.06em;}
        .d-nav{flex:1;padding:12px;overflow-y:auto;}
        .d-ni{display:flex;align-items:center;gap:12px;padding:13px 14px;border-radius:11px;text-decoration:none;color:var(--soft);font-size:.9rem;font-weight:500;transition:background .15s,color .15s;margin-bottom:3px;position:relative;}
        .d-ni svg{width:19px;height:19px;flex-shrink:0;opacity:.6;}
        .d-ni:hover{background:rgba(255,255,255,.06);color:var(--white);}
        .d-ni.active{background:rgba(79,156,249,.12);color:var(--accent2);}
        .d-ni.active svg{opacity:1;}
        .d-nb{margin-left:auto;background:rgba(251,191,36,.12);color:var(--warn);font-size:.65rem;font-weight:800;padding:2px 8px;border-radius:99px;}
        .d-foot{padding:16px 20px;border-top:1px solid rgba(255,255,255,.07);}
        .d-logout{display:flex;align-items:center;gap:10px;width:100%;padding:12px 14px;background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.15);border-radius:10px;color:var(--danger);font-family:'Outfit',sans-serif;font-size:.875rem;font-weight:600;cursor:pointer;}
        .d-logout svg{width:18px;height:18px;}
        .d-logout:hover{background:rgba(248,113,113,.15);}

        /* ════════════ SHARED COMPONENTS ════════════ */
        .gc{background:var(--glass);border:1px solid var(--gbd);border-radius:var(--rl);backdrop-filter:blur(16px);box-shadow:inset 0 1px 0 rgba(255,255,255,.07);}
        .gci{padding:24px;}
        .ct{font-size:1rem;font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
        .ctl{flex:1;height:1px;background:rgba(255,255,255,.07);min-width:20px;}
        .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
        .dt{width:100%;border-collapse:collapse;min-width:480px;}
        .dt th{text-align:left;font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);padding:10px 12px;border-bottom:1px solid rgba(255,255,255,.07);}
        .dt td{padding:12px 12px;font-size:.85rem;color:var(--soft);border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle;}
        .dt tr:last-child td{border-bottom:none;}
        .dt tr:hover td{background:rgba(255,255,255,.02);}
        .btn{display:inline-flex;align-items:center;gap:5px;padding:7px 12px;border-radius:var(--rs);font-family:'Outfit',sans-serif;font-size:.78rem;font-weight:600;border:none;cursor:pointer;transition:opacity .15s,transform .12s;text-decoration:none;white-space:nowrap;}
        .btn:hover{opacity:.85;transform:translateY(-1px);}
        .btn svg{width:12px;height:12px;}
        .btn-blue{background:linear-gradient(135deg,#4f9cf9,#1a6fe8);color:#fff;box-shadow:0 2px 12px rgba(79,156,249,.25);}
        .btn-danger{background:rgba(248,113,113,.1);color:var(--danger);border:1px solid rgba(248,113,113,.2);}
        .btn-warn{background:rgba(251,191,36,.1);color:var(--warn);border:1px solid rgba(251,191,36,.2);}
        .btn-success{background:rgba(79,156,249,.1);color:var(--accent2);border:1px solid rgba(79,156,249,.2);}
        .btn-ghost{background:rgba(255,255,255,.05);color:var(--soft);border:1px solid rgba(255,255,255,.1);}
        .alert{padding:11px 14px;border-radius:var(--rs);font-size:.83rem;margin-bottom:16px;}
        .alert-success{background:rgba(79,156,249,.08);color:var(--accent2);border:1px solid rgba(79,156,249,.18);}
        .alert-danger{background:rgba(248,113,113,.08);color:var(--danger);border:1px solid rgba(248,113,113,.18);}
        .field{margin-bottom:14px;}
        .field label{display:block;font-size:.7rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:6px;}
        .field input,.field select,.field textarea{width:100%;padding:10px 14px;font-family:'Outfit',sans-serif;font-size:.875rem;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:var(--rs);color:var(--white);outline:none;transition:border-color .2s;}
        .field input::placeholder,.field textarea::placeholder{color:var(--muted);}
        .field input:focus,.field select:focus,.field textarea:focus{border-color:rgba(79,156,249,.5);box-shadow:0 0 0 3px rgba(79,156,249,.1);}
        .field select option{background:#060d1a;}
        .field textarea{resize:vertical;min-height:80px;}
        .modal-overlay{display:none;position:fixed;inset:0;z-index:500;background:rgba(0,0,0,.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:16px;}
        .modal-overlay.open{display:flex;}
        .modal{background:#060d1a;border:1px solid rgba(255,255,255,.1);border-radius:var(--rl);padding:28px;width:100%;max-width:440px;animation:mi .25s ease both;box-shadow:0 20px 60px rgba(0,0,0,.5),inset 0 1px 0 rgba(255,255,255,.08);max-height:90vh;overflow-y:auto;}
        @keyframes mi{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
        .modal-title{font-size:1.1rem;font-weight:700;margin-bottom:6px;}
        .modal-sub{font-size:.83rem;color:var(--soft);margin-bottom:20px;}
        .modal-actions{display:flex;gap:10px;margin-top:20px;flex-wrap:wrap;}
        .modal-actions .btn{flex:1;justify-content:center;}
        .sbadge{display:inline-block;padding:3px 9px;border-radius:99px;font-size:.65rem;font-weight:700;letter-spacing:.06em;}
        .sa{background:rgba(79,156,249,.12);color:var(--accent2);border:1px solid rgba(79,156,249,.2);}
        .si{background:rgba(248,113,113,.1);color:var(--danger);border:1px solid rgba(248,113,113,.2);}
        .sp{background:rgba(251,191,36,.1);color:var(--warn);border:1px solid rgba(251,191,36,.2);}
        .sr{background:rgba(248,113,113,.1);color:var(--danger);border:1px solid rgba(248,113,113,.2);}
        .empty{text-align:center;padding:40px 20px;color:var(--muted);font-size:.875rem;}
        .empty svg{width:32px;height:32px;margin:0 auto 12px;opacity:.2;display:block;}
        .search-bar{display:flex;align-items:center;gap:8px;margin-bottom:16px;flex-wrap:wrap;}
        .siw{position:relative;flex:1;min-width:160px;}
        .siw svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--muted);pointer-events:none;}
        .si-inp{width:100%;padding:9px 14px 9px 36px;font-family:'Outfit',sans-serif;font-size:.875rem;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:var(--rs);color:var(--white);outline:none;}
        .si-inp::placeholder{color:var(--muted);}
        .si-inp:focus{border-color:rgba(79,156,249,.5);box-shadow:0 0 0 3px rgba(79,156,249,.1);}
        .fsel{padding:9px 14px;font-family:'Outfit',sans-serif;font-size:.875rem;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:var(--rs);color:var(--white);outline:none;cursor:pointer;}
        .fsel option{background:#060d1a;}

        /* timeout banner */
        .t-banner{display:none;position:fixed;bottom:16px;right:16px;z-index:9999;background:rgba(4,8,15,.97);border:1px solid rgba(251,191,36,.3);border-radius:14px;padding:14px 18px;box-shadow:0 8px 32px rgba(0,0,0,.5);max-width:calc(100vw - 32px);backdrop-filter:blur(16px);}
        .t-banner.show{display:block;}
        .t-title{font-size:.85rem;font-weight:700;color:var(--warn);margin-bottom:4px;}
        .t-sub{font-size:.75rem;color:var(--soft);}
        .t-count{font-size:1.2rem;font-weight:800;color:var(--warn);margin:8px 0;}
        .t-btn{padding:7px 16px;background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.25);border-radius:8px;color:var(--warn);font-family:'Outfit',sans-serif;font-size:.8rem;font-weight:600;cursor:pointer;}

        /* ════════════ BREAKPOINTS ════════════ */
        /* Desktop: 900px and above */
        @media(min-width:900px){
            .mobile-topbar,.mobile-content,.drawer,.drawer-overlay{display:none!important;}
            .desktop-sidebar{display:flex;}
            .desktop-main{display:block;}
        }
        /* Mobile: below 900px */
        @media(max-width:899px){
            .desktop-sidebar,.desktop-main{display:none!important;}
            .mobile-topbar{display:flex;}
            .mobile-content{display:block;}
            .gci{padding:16px;}
        }
    </style>
    @yield('styles')
</head>
<body>
@php $pc=\App\Models\User::where('status','pending')->count(); @endphp

{{-- ════ DESKTOP SIDEBAR ════ --}}
<aside class="desktop-sidebar">
    <a href="{{ route('admin.dashboard') }}" class="s-logo">
        <svg viewBox="0 0 20 20" fill="none"><path d="M10 2L3 6v8l7 4 7-4V6L10 2z" fill="#fff" fill-opacity=".9"/><path d="M10 2v12M3 6l7 4 7-4" stroke="#fff" stroke-opacity=".5" stroke-width="1.2"/></svg>
    </a>
    <nav class="s-nav">
        <a href="{{ route('admin.dashboard') }}" class="ni {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            <span class="tip">Dashboard</span>
        </a>
        <a href="{{ route('admin.spaces') }}" class="ni {{ request()->routeIs('admin.spaces') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span class="tip">Spaces</span>
        </a>
        <a href="{{ route('admin.qr-codes') }}" class="ni {{ request()->routeIs('admin.qr-codes') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="5" height="5"/><rect x="16" y="3" width="5" height="5"/>
        <rect x="3" y="16" width="5" height="5"/><line x1="16" y1="16" x2="21" y2="21"/>
    </svg>
    <span class="tip">QR Codes</span>
</a>
        <a href="{{ route('admin.users') }}" class="ni {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span class="tip">Users</span>
        </a>
        <a href="{{ route('admin.verifications') }}" class="ni {{ request()->routeIs('admin.verifications') ? 'active' : '' }}" style="position:relative;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            @if($pc>0)<span class="nbadge">{{ $pc }}</span>@endif
            <span class="tip">Verifications</span>
        </a>
        <a href="{{ route('admin.admins') }}" class="ni {{ request()->routeIs('admin.admins') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span class="tip">Admins</span>
        </a>
        <a href="{{ route('admin.activity-logs') }}" class="ni {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            <span class="tip">Activity Logs</span>
        </a>
    </nav>
    <div class="s-bot">
        <div class="s-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <form method="POST" action="{{ route('logout') }}" id="lf-desktop">@csrf
            <button type="submit" class="s-lo" title="Sign out">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </button>
        </form>
    </div>
</aside>

{{-- ════ DESKTOP MAIN ════ --}}
<div class="desktop-main">
    <div class="desktop-topbar">
        <div class="topbar-title"><h1>@yield('page-title')</h1><p>@yield('page-sub')</p></div>
        <div class="abadge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Admin · {{ auth()->user()->name }}</div>
    </div>
    <div class="desktop-content">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @yield('content')
    </div>
</div>

{{-- ════ MOBILE TOPBAR ════ --}}
<div class="mobile-topbar">
    <a href="{{ route('admin.dashboard') }}" class="mob-brand">
        <div class="mob-icon"><svg viewBox="0 0 20 20" fill="none"><path d="M10 2L3 6v8l7 4 7-4V6L10 2z" fill="#fff" fill-opacity=".9"/><path d="M10 2v12M3 6l7 4 7-4" stroke="#fff" stroke-opacity=".5" stroke-width="1.2"/></svg></div>
        <span class="mob-name">Campu<span>SIMS</span></span>
    </a>
    <div class="mob-right">
        @if($pc>0)<span class="mob-pending">{{ $pc }} pending</span>@endif
        <button class="ham" onclick="openDrawer()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
    </div>
</div>

{{-- ════ MOBILE CONTENT ════ --}}
<div class="mobile-content">
    <div class="mob-page-header"><h2>@yield('page-title')</h2><p>@yield('page-sub')</p></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @yield('content')
</div>

{{-- Drawer --}}
<div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
<div class="drawer" id="drawer">
    <div class="d-head">
        <div class="d-brand"><div class="d-brand-icon"><svg viewBox="0 0 20 20" fill="none"><path d="M10 2L3 6v8l7 4 7-4V6L10 2z" fill="#fff" fill-opacity=".9"/><path d="M10 2v12M3 6l7 4 7-4" stroke="#fff" stroke-opacity=".5" stroke-width="1.2"/></svg></div><span class="d-brand-name">Campu<span>SIMS</span></span></div>
        <button class="d-close" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <div class="d-user">
        <div class="d-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div><div class="d-uname">{{ auth()->user()->name }}</div><div class="d-urole">Admin</div></div>
    </div>
    <nav class="d-nav">
        <a href="{{ route('admin.dashboard') }}" class="d-ni {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard</a>
        <a href="{{ route('admin.spaces') }}" class="d-ni {{ request()->routeIs('admin.spaces') ? 'active' : '' }}" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Spaces</a>
        <a href="{{ route('admin.users') }}" class="d-ni {{ request()->routeIs('admin.users') ? 'active' : '' }}" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Users</a>
        <a href="{{ route('admin.verifications') }}" class="d-ni {{ request()->routeIs('admin.verifications') ? 'active' : '' }}" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>Verifications @if($pc>0)<span class="d-nb">{{ $pc }}</span>@endif</a>
        <a href="{{ route('admin.admins') }}" class="d-ni {{ request()->routeIs('admin.admins') ? 'active' : '' }}" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Admins</a>
        <a href="{{ route('admin.activity-logs') }}" class="d-ni {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>Activity Logs</a>
    </nav>
    <div class="d-foot">
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="d-logout"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Sign Out</button>
        </form>
    </div>
</div>

<div class="t-banner" id="tb">
    <div class="t-title">⚠ Session Expiring</div>
    <div class="t-sub">Logged out due to inactivity in:</div>
    <div class="t-count" id="tc">60s</div>
    <button class="t-btn" onclick="rt()">Stay Logged In</button>
</div>

<script>
function openDrawer(){document.getElementById('drawer').classList.add('open');document.getElementById('drawerOverlay').classList.add('open');document.body.style.overflow='hidden';}
function closeDrawer(){document.getElementById('drawer').classList.remove('open');document.getElementById('drawerOverlay').classList.remove('open');document.body.style.overflow='';}
let _tx=0;
document.getElementById('drawer').addEventListener('touchstart',e=>{_tx=e.touches[0].clientX;},{passive:true});
document.getElementById('drawer').addEventListener('touchend',e=>{if(_tx-e.changedTouches[0].clientX>50)closeDrawer();},{passive:true});
let rem=3600,warned=false,cd=60,ci;
function rt(){rem=3600;warned=false;clearInterval(ci);document.getElementById('tb').classList.remove('show');}
function sc(){cd=60;document.getElementById('tb').classList.add('show');ci=setInterval(()=>{cd--;document.getElementById('tc').textContent=cd+'s';if(cd<=0){clearInterval(ci);submitLogout();}},1000);}
function submitLogout(){const f=document.getElementById('lf-desktop')||document.querySelector('form[action*="logout"]');if(f)f.submit();}
setInterval(()=>{rem--;if(rem<=60&&!warned){warned=true;sc();}if(rem<=0)submitLogout();},1000);
['mousemove','keydown','click','scroll','touchstart'].forEach(e=>document.addEventListener(e,rt,{passive:true}));
</script>
@yield('scripts')
</body>
</html>