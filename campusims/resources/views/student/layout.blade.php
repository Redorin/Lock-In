<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CampuSIMS — @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ══════════════════════════════════════
           THEME VARIABLES
        ══════════════════════════════════════ */
        :root {
            --transition: background .25s, color .25s, border-color .25s, box-shadow .25s;
        }

        /* ── DARK MODE (default) ── */
        [data-theme="dark"] {
            --bg:            #04080f;
            --bg2:           #060d1a;
            --surface:       rgba(255,255,255,.04);
            --surface2:      rgba(255,255,255,.07);
            --border:        rgba(255,255,255,.08);
            --border2:       rgba(255,255,255,.13);
            --text:          rgba(255,255,255,.92);
            --text-soft:     rgba(255,255,255,.55);
            --text-muted:    rgba(255,255,255,.28);
            --accent:        #4f9cf9;
            --accent2:       #7eb8ff;
            --accent-glow:   rgba(79,156,249,.35);
            --accent-bg:     rgba(79,156,249,.12);
            --accent-border: rgba(79,156,249,.25);
            --danger:        #f87171;
            --danger-bg:     rgba(248,113,113,.1);
            --danger-border: rgba(248,113,113,.2);
            --warn:          #fbbf24;
            --warn-bg:       rgba(251,191,36,.1);
            --warn-border:   rgba(251,191,36,.2);
            --success-bg:    rgba(79,156,249,.08);
            --success-text:  #7eb8ff;
            --sidebar-bg:    rgba(255,255,255,.04);
            --sidebar-top:   rgba(4,8,15,.97);
            --drawer-bg:     #060d1a;
            --modal-bg:      #060d1a;
            --card-inset:    inset 0 1px 0 rgba(255,255,255,.07);
            --mesh1:         rgba(30,80,200,.2);
            --mesh2:         rgba(100,60,220,.12);
            --mesh3:         rgba(79,156,249,.05);
            --toggle-bg:     rgba(255,255,255,.08);
            --toggle-icon:   rgba(255,255,255,.6);
        }

        /* ── LIGHT MODE ── */
        [data-theme="light"] {
            --bg:            #f0f4f8;
            --bg2:           #e8eef5;
            --surface:       #ffffff;
            --surface2:      #f5f8fc;
            --border:        rgba(0,0,0,.08);
            --border2:       rgba(0,0,0,.13);
            --text:          #0f1923;
            --text-soft:     #4a5568;
            --text-muted:    #94a3b8;
            --accent:        #2563eb;
            --accent2:       #1d4ed8;
            --accent-glow:   rgba(37,99,235,.2);
            --accent-bg:     rgba(37,99,235,.08);
            --accent-border: rgba(37,99,235,.2);
            --danger:        #dc2626;
            --danger-bg:     rgba(220,38,38,.08);
            --danger-border: rgba(220,38,38,.2);
            --warn:          #d97706;
            --warn-bg:       rgba(217,119,6,.08);
            --warn-border:   rgba(217,119,6,.2);
            --success-bg:    rgba(37,99,235,.07);
            --success-text:  #1d4ed8;
            --sidebar-bg:    #ffffff;
            --sidebar-top:   #ffffff;
            --drawer-bg:     #ffffff;
            --modal-bg:      #ffffff;
            --card-inset:    inset 0 1px 0 rgba(255,255,255,.8);
            --mesh1:         rgba(37,99,235,.06);
            --mesh2:         rgba(99,102,241,.04);
            --mesh3:         rgba(37,99,235,.03);
            --toggle-bg:     rgba(0,0,0,.06);
            --toggle-icon:   #64748b;
        }

        /* ══════════════════════════════════════
           BASE
        ══════════════════════════════════════ */
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html{background:var(--bg);}
        body{
            font-family:'Outfit',sans-serif;
            background:var(--bg);
            color:var(--text);
            -webkit-font-smoothing:antialiased;
            margin:0;padding:0;
            transition:var(--transition);
        }
        body::before{
            content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
            background:
                radial-gradient(ellipse 70% 55% at 5% 5%,  var(--mesh1) 0%,transparent 55%),
                radial-gradient(ellipse 50% 50% at 90% 90%,var(--mesh2) 0%,transparent 55%),
                radial-gradient(ellipse 35% 35% at 55% 15%,var(--mesh3) 0%,transparent 50%);
            transition:var(--transition);
        }

        /* ══════════════════════════════════════
           DESKTOP SIDEBAR
        ══════════════════════════════════════ */
        .desktop-sidebar{
            position:fixed;top:0;left:0;bottom:0;width:72px;z-index:100;
            background:var(--sidebar-bg);
            border-right:1px solid var(--border);
            backdrop-filter:blur(28px);
            display:flex;flex-direction:column;align-items:center;padding:18px 0;
            box-shadow:var(--card-inset);
            transition:var(--transition);
        }
        .s-logo{width:42px;height:42px;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);border-radius:13px;display:flex;align-items:center;justify-content:center;margin-bottom:28px;text-decoration:none;box-shadow:0 4px 20px var(--accent-glow);flex-shrink:0;}
        .s-logo svg{width:20px;height:20px;}
        .s-nav{display:flex;flex-direction:column;align-items:center;gap:4px;flex:1;width:100%;padding:0 10px;}
        .ni{width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;text-decoration:none;color:var(--text-muted);position:relative;transition:background .18s,color .18s;}
        .ni svg{width:20px;height:20px;transition:transform .18s;}
        .ni:hover{background:var(--surface2);color:var(--text-soft);}
        .ni:hover svg{transform:scale(1.1);}
        .ni.active{background:var(--accent-bg);color:var(--accent);}
        .ni.active::before{content:'';position:absolute;left:-10px;width:3px;height:22px;background:var(--accent);border-radius:0 3px 3px 0;box-shadow:0 0 10px var(--accent-glow);}
        .ni .tip{position:absolute;left:58px;background:var(--modal-bg);border:1px solid var(--border2);color:var(--text);font-size:.73rem;font-weight:500;padding:5px 10px;border-radius:8px;white-space:nowrap;opacity:0;pointer-events:none;transform:translateX(-6px);transition:opacity .15s,transform .15s;z-index:999;box-shadow:0 4px 16px rgba(0,0,0,.15);}
        .ni:hover .tip{opacity:1;transform:translateX(0);}
        .s-bot{display:flex;flex-direction:column;align-items:center;gap:8px;padding:0 10px;}
        .s-av{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:700;color:#fff;border:2px solid var(--accent-border);}
        .s-lo{width:38px;height:38px;border-radius:10px;background:transparent;border:1px solid var(--border2);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-muted);transition:all .18s;}
        .s-lo svg{width:16px;height:16px;}
        .s-lo:hover{background:var(--danger-bg);color:var(--danger);border-color:var(--danger-border);}

        /* theme toggle button in sidebar */
        .theme-toggle{width:38px;height:38px;border-radius:10px;background:var(--toggle-bg);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--toggle-icon);transition:all .18s;}
        .theme-toggle svg{width:17px;height:17px;}
        .theme-toggle:hover{background:var(--accent-bg);color:var(--accent);border-color:var(--accent-border);}
        .icon-sun{display:none;}
        .icon-moon{display:block;}
        [data-theme="light"] .icon-sun{display:block;}
        [data-theme="light"] .icon-moon{display:none;}

        /* ══════════════════════════════════════
           DESKTOP MAIN
        ══════════════════════════════════════ */
        .desktop-main{margin-left:72px;min-height:100vh;position:relative;z-index:1;}
        .desktop-topbar{display:flex;align-items:center;justify-content:space-between;padding:20px 28px 0;flex-wrap:wrap;gap:12px;}
        .topbar-title h1{font-size:1.65rem;font-weight:800;letter-spacing:-.5px;color:var(--text);}
        .topbar-title p{font-size:.83rem;color:var(--text-soft);margin-top:3px;}
        .gpill{display:flex;align-items:center;gap:8px;background:var(--surface);border:1px solid var(--border2);border-radius:99px;padding:7px 16px 7px 10px;font-size:.82rem;color:var(--text-soft);box-shadow:0 2px 8px rgba(0,0,0,.06);}
        .pdot{width:8px;height:8px;border-radius:50%;background:var(--accent);box-shadow:0 0 8px var(--accent-glow);animation:pulse 2s infinite;}
        @keyframes pulse{0%{box-shadow:0 0 0 0 var(--accent-glow)}70%{box-shadow:0 0 0 6px transparent}100%{box-shadow:0 0 0 0 transparent}}
        .desktop-content{padding:20px 28px 40px;}

        /* ══════════════════════════════════════
           MOBILE TOPBAR
        ══════════════════════════════════════ */
        .mobile-topbar{display:none;align-items:center;justify-content:space-between;padding:13px 18px;background:var(--sidebar-top);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:50;backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);transition:var(--transition);}
        .mob-brand{display:flex;align-items:center;gap:10px;text-decoration:none;}
        .mob-icon{width:34px;height:34px;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);border-radius:9px;display:flex;align-items:center;justify-content:center;}
        .mob-icon svg{width:16px;height:16px;}
        .mob-name{font-size:1rem;font-weight:800;letter-spacing:-.3px;color:var(--text);}
        .mob-name span{color:var(--accent);}
        .mob-right{display:flex;align-items:center;gap:8px;}
        .ham{width:38px;height:38px;background:var(--surface2);border:1px solid var(--border);border-radius:9px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-soft);}
        .ham svg{width:20px;height:20px;}
        /* mobile theme toggle */
        .mob-theme{width:38px;height:38px;background:var(--toggle-bg);border:1px solid var(--border);border-radius:9px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--toggle-icon);}
        .mob-theme svg{width:18px;height:18px;}

        .mobile-content{display:none;padding:16px 16px 40px;position:relative;z-index:1;}
        .mob-page-header{margin-bottom:16px;}
        .mob-page-header h2{font-size:1.3rem;font-weight:800;letter-spacing:-.4px;color:var(--text);}
        .mob-page-header p{font-size:.78rem;color:var(--text-soft);margin-top:2px;}

        /* ══════════════════════════════════════
           DRAWER
        ══════════════════════════════════════ */
        .drawer-overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.5);backdrop-filter:blur(4px);}
        .drawer-overlay.open{display:block;}
        .drawer{position:fixed;top:0;left:-290px;bottom:0;width:290px;z-index:201;background:var(--drawer-bg);border-right:1px solid var(--border2);transition:left .28s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;overflow:hidden;box-shadow:4px 0 24px rgba(0,0,0,.15);}
        .drawer.open{left:0;}
        .d-head{display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:1px solid var(--border);}
        .d-brand{display:flex;align-items:center;gap:10px;}
        .d-brand-icon{width:36px;height:36px;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);border-radius:10px;display:flex;align-items:center;justify-content:center;}
        .d-brand-icon svg{width:18px;height:18px;}
        .d-brand-name{font-size:1rem;font-weight:800;letter-spacing:-.3px;color:var(--text);}
        .d-brand-name span{color:var(--accent);}
        .d-close{width:32px;height:32px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-soft);}
        .d-close svg{width:16px;height:16px;}
        .d-user{display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border);}
        .d-av{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:800;color:#fff;border:2px solid var(--accent-border);flex-shrink:0;}
        .d-uname{font-size:.92rem;font-weight:600;color:var(--text);}
        .d-urole{font-size:.72rem;color:var(--text-muted);text-transform:capitalize;}
        .d-nav{flex:1;padding:12px;overflow-y:auto;}
        .d-ni{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:11px;text-decoration:none;color:var(--text-soft);font-size:.9rem;font-weight:500;transition:background .15s,color .15s;margin-bottom:3px;}
        .d-ni svg{width:19px;height:19px;flex-shrink:0;opacity:.7;}
        .d-ni:hover{background:var(--surface2);color:var(--text);}
        .d-ni.active{background:var(--accent-bg);color:var(--accent);}
        .d-ni.active svg{opacity:1;}
        .d-foot{padding:14px 20px;border-top:1px solid var(--border);}
        /* theme toggle in drawer */
        .d-theme{display:flex;align-items:center;gap:10px;width:100%;padding:11px 14px;background:var(--toggle-bg);border:1px solid var(--border);border-radius:10px;color:var(--text-soft);font-family:'Outfit',sans-serif;font-size:.875rem;font-weight:500;cursor:pointer;margin-bottom:10px;transition:background .15s;}
        .d-theme svg{width:18px;height:18px;}
        .d-theme:hover{background:var(--accent-bg);color:var(--accent);}
        .d-logout{display:flex;align-items:center;gap:10px;width:100%;padding:11px 14px;background:var(--danger-bg);border:1px solid var(--danger-border);border-radius:10px;color:var(--danger);font-family:'Outfit',sans-serif;font-size:.875rem;font-weight:600;cursor:pointer;}
        .d-logout svg{width:18px;height:18px;}
        .d-logout:hover{background:rgba(220,38,38,.15);}

        /* ══════════════════════════════════════
           SHARED COMPONENTS
        ══════════════════════════════════════ */
        .gc{background:var(--surface);border:1px solid var(--border);border-radius:22px;backdrop-filter:blur(16px);box-shadow:var(--card-inset),0 2px 12px rgba(0,0,0,.06);transition:var(--transition);}
        .alert{padding:12px 16px;border-radius:14px;font-size:.875rem;margin-bottom:16px;}
        .alert-success{background:var(--success-bg);color:var(--success-text);border:1px solid var(--accent-border);}
        .alert-danger{background:var(--danger-bg);color:var(--danger);border:1px solid var(--danger-border);}
        .field{margin-bottom:18px;}
        .field label{display:block;font-size:.72rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:7px;}
        .field input{width:100%;padding:12px 16px;font-family:'Outfit',sans-serif;font-size:.9rem;background:var(--surface2);border:1px solid var(--border2);border-radius:14px;color:var(--text);outline:none;transition:border-color .2s,background .2s,box-shadow .2s;}
        .field input::placeholder{color:var(--text-muted);}
        .field input:focus{border-color:var(--accent);background:var(--surface);box-shadow:0 0 0 3px var(--accent-bg);}
        .field input.is-invalid{border-color:var(--danger);}
        .invalid-feedback{font-size:.78rem;color:var(--danger);margin-top:5px;}

        /* timeout banner */
        .t-banner{display:none;position:fixed;bottom:16px;right:16px;z-index:9999;background:var(--surface);border:1px solid var(--warn-border);border-radius:14px;padding:14px 18px;box-shadow:0 8px 32px rgba(0,0,0,.15);max-width:calc(100vw - 32px);backdrop-filter:blur(16px);}
        .t-banner.show{display:block;}
        .t-title{font-size:.85rem;font-weight:700;color:var(--warn);margin-bottom:4px;}
        .t-sub{font-size:.75rem;color:var(--text-soft);}
        .t-count{font-size:1.2rem;font-weight:800;color:var(--warn);margin:8px 0;}
        .t-btn{padding:7px 16px;background:var(--warn-bg);border:1px solid var(--warn-border);border-radius:8px;color:var(--warn);font-family:'Outfit',sans-serif;font-size:.8rem;font-weight:600;cursor:pointer;}

        /* ══════════════════════════════════════
           BREAKPOINTS
        ══════════════════════════════════════ */
        @media(min-width:900px){
            .mobile-topbar,.mobile-content,.drawer,.drawer-overlay{display:none!important;}
            .desktop-sidebar{display:flex;}
            .desktop-main{display:block;}
        }
        @media(max-width:899px){
            .desktop-sidebar,.desktop-main{display:none!important;}
            .mobile-topbar{display:flex;}
            .mobile-content{display:block;}
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- ════ DESKTOP SIDEBAR ════ --}}
<aside class="desktop-sidebar">
    <a href="{{ route('student.dashboard') }}" class="s-logo">
        <svg viewBox="0 0 20 20" fill="none"><path d="M10 2L3 6v8l7 4 7-4V6L10 2z" fill="#fff" fill-opacity=".9"/><path d="M10 2v12M3 6l7 4 7-4" stroke="#fff" stroke-opacity=".5" stroke-width="1.2"/></svg>
    </a>
    <nav class="s-nav">
        <a href="{{ route('student.dashboard') }}" class="ni {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            <span class="tip">Dashboard</span>
        </a>
        <a href="{{ route('student.map') }}" class="ni {{ request()->routeIs('student.map') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
            <span class="tip">Campus Map</span>
        </a>
        <a href="{{ route('student.scanner') }}" class="ni {{ request()->routeIs('student.scanner','student.checkin*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="5" height="5"/><rect x="16" y="3" width="5" height="5"/><rect x="3" y="16" width="5" height="5"/><line x1="16" y1="16" x2="21" y2="16"/><line x1="16" y1="21" x2="21" y2="21"/><line x1="16" y1="16" x2="16" y2="21"/></svg>
            <span class="tip">QR Scanner</span>
        </a>
        <a href="{{ route('student.id-card') }}" class="ni {{ request()->routeIs('student.id-card') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8" cy="12" r="2.5"/><line x1="13" y1="10" x2="19" y2="10"/><line x1="13" y1="14" x2="17" y2="14"/></svg>
            <span class="tip">My ID Card</span>
        </a>
        <a href="{{ route('student.settings') }}" class="ni {{ request()->routeIs('student.settings') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            <span class="tip">Settings</span>
        </a>
    </nav>
    <div class="s-bot">
        <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
            <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            <svg class="icon-sun"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        </button>
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
        <div class="gpill"><span class="pdot"></span>Hi, {{ explode(' ', auth()->user()->name)[0] }}!</div>
    </div>
    <div class="desktop-content">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @yield('content')
    </div>
</div>

{{-- ════ MOBILE TOPBAR ════ --}}
<div class="mobile-topbar">
    <a href="{{ route('student.dashboard') }}" class="mob-brand">
        <div class="mob-icon"><svg viewBox="0 0 20 20" fill="none"><path d="M10 2L3 6v8l7 4 7-4V6L10 2z" fill="#fff" fill-opacity=".9"/><path d="M10 2v12M3 6l7 4 7-4" stroke="#fff" stroke-opacity=".5" stroke-width="1.2"/></svg></div>
        <span class="mob-name">Campu<span>SIMS</span></span>
    </a>
    <div class="mob-right">
        <button class="mob-theme" onclick="toggleTheme()" title="Toggle theme">
            <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            <svg class="icon-sun"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        </button>
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
        <div><div class="d-uname">{{ auth()->user()->name }}</div><div class="d-urole">{{ auth()->user()->role }}</div></div>
    </div>
    <nav class="d-nav">
        <a href="{{ route('student.dashboard') }}" class="d-ni {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard</a>
        <a href="{{ route('student.map') }}" class="d-ni {{ request()->routeIs('student.map') ? 'active' : '' }}" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>Campus Map</a>
        <a href="{{ route('student.scanner') }}" class="d-ni {{ request()->routeIs('student.scanner') ? 'active' : '' }}" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="5" height="5"/><rect x="16" y="3" width="5" height="5"/><rect x="3" y="16" width="5" height="5"/><line x1="16" y1="16" x2="21" y2="16"/><line x1="16" y1="21" x2="21" y2="21"/><line x1="16" y1="16" x2="16" y2="21"/></svg>QR Scanner</a>
        <a href="{{ route('student.id-card') }}" class="d-ni {{ request()->routeIs('student.id-card') ? 'active' : '' }}" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8" cy="12" r="2.5"/><line x1="13" y1="10" x2="19" y2="10"/><line x1="13" y1="14" x2="17" y2="14"/></svg>My ID Card</a>
        <a href="{{ route('student.settings') }}" class="d-ni {{ request()->routeIs('student.settings') ? 'active' : '' }}" onclick="closeDrawer()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>Settings</a>
    </nav>
    <div class="d-foot">
        <button class="d-theme" onclick="toggleTheme()">
            <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            <svg class="icon-sun"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
            <span id="themeLabel">Switch to Light Mode</span>
        </button>
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
// ── Theme ─────────────────────────────────────────────────────────────────────
const html = document.documentElement;

function applyTheme(theme) {
    html.setAttribute('data-theme', theme);
    localStorage.setItem('campusims_theme', theme);
    const label = document.getElementById('themeLabel');
    if (label) label.textContent = theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode';
}

function toggleTheme() {
    const current = html.getAttribute('data-theme');
    applyTheme(current === 'dark' ? 'light' : 'dark');
}

// Apply saved theme immediately (before paint)
(function() {
    const saved = localStorage.getItem('campusims_theme') || 'dark';
    applyTheme(saved);
})();

// ── Drawer ────────────────────────────────────────────────────────────────────
function openDrawer(){document.getElementById('drawer').classList.add('open');document.getElementById('drawerOverlay').classList.add('open');document.body.style.overflow='hidden';}
function closeDrawer(){document.getElementById('drawer').classList.remove('open');document.getElementById('drawerOverlay').classList.remove('open');document.body.style.overflow='';}
let _tx=0;
document.getElementById('drawer').addEventListener('touchstart',e=>{_tx=e.touches[0].clientX;},{passive:true});
document.getElementById('drawer').addEventListener('touchend',e=>{if(_tx-e.changedTouches[0].clientX>50)closeDrawer();},{passive:true});

// ── Session timeout ───────────────────────────────────────────────────────────
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