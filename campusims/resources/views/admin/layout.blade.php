<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampuSIMS Admin — @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:#091510;--glass:rgba(255,255,255,.04);--glass-hover:rgba(255,255,255,.07);
            --glass-border:rgba(255,255,255,.08);--accent:#00e5a0;--accent2:#7c6ff7;
            --accent3:#f59e0b;--danger:#ff7070;--text:#ddeee6;
            --text-soft:rgba(221,238,230,.55);--text-muted:rgba(221,238,230,.28);
            --sidebar-w:72px;--radius-lg:22px;--radius-md:14px;--radius-sm:10px;
        }
        html,body{height:100%;font-family:'Outfit',sans-serif;background:var(--bg);color:var(--text);-webkit-font-smoothing:antialiased;overflow:hidden;}
        body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
            background:radial-gradient(ellipse 70% 55% at 5% 5%,rgba(124,111,247,.12) 0%,transparent 55%),
                radial-gradient(ellipse 50% 50% at 90% 90%,rgba(0,229,160,.10) 0%,transparent 55%),
                radial-gradient(ellipse 35% 35% at 55% 15%,rgba(245,158,11,.06) 0%,transparent 50%);}
        .app{position:relative;z-index:1;display:flex;height:100vh;padding:14px;gap:14px;}

        /* ══ SIDEBAR ══ */
        .sidebar{width:var(--sidebar-w);flex-shrink:0;background:rgba(255,255,255,.03);border:1px solid var(--glass-border);border-radius:var(--radius-lg);backdrop-filter:blur(24px);display:flex;flex-direction:column;align-items:center;padding:18px 0;}
        .sidebar-logo{width:42px;height:42px;background:linear-gradient(135deg,var(--accent2),#5147c9);border-radius:13px;display:flex;align-items:center;justify-content:center;margin-bottom:30px;text-decoration:none;box-shadow:0 4px 20px rgba(124,111,247,.3);flex-shrink:0;}
        .sidebar-logo svg{width:20px;height:20px;}
        .sidebar-nav{display:flex;flex-direction:column;align-items:center;gap:4px;flex:1;width:100%;padding:0 10px;}
        .nav-item{width:48px;height:48px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;text-decoration:none;color:var(--text-muted);position:relative;transition:background .18s,color .18s;}
        .nav-item svg{width:20px;height:20px;transition:transform .18s;}
        .nav-item:hover{background:var(--glass-hover);color:var(--text-soft);}
        .nav-item:hover svg{transform:scale(1.1);}
        .nav-item.active{background:rgba(124,111,247,.15);color:var(--accent2);}
        .nav-item.active::before{content:'';position:absolute;left:-10px;width:3px;height:22px;background:var(--accent2);border-radius:0 3px 3px 0;box-shadow:0 0 10px var(--accent2);}
        .nav-item .tip{position:absolute;left:58px;background:rgba(9,21,16,.96);border:1px solid var(--glass-border);color:var(--text);font-size:.73rem;font-weight:500;padding:5px 10px;border-radius:8px;white-space:nowrap;opacity:0;pointer-events:none;transform:translateX(-6px);transition:opacity .15s,transform .15s;z-index:999;}
        .nav-item:hover .tip{opacity:1;transform:translateX(0);}
        .nav-badge{position:absolute;top:6px;right:6px;width:16px;height:16px;border-radius:50%;background:var(--accent3);color:#1a0f00;font-size:.6rem;font-weight:800;display:flex;align-items:center;justify-content:center;}
        .sidebar-bottom{display:flex;flex-direction:column;align-items:center;gap:10px;padding:0 10px;}
        .avatar-wrap{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--accent2),#5147c9);display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:700;color:#fff;border:2px solid rgba(124,111,247,.35);}
        .logout-btn{width:38px;height:38px;border-radius:var(--radius-sm);background:transparent;border:1px solid var(--glass-border);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-muted);transition:background .18s,color .18s,border-color .18s;}
        .logout-btn svg{width:16px;height:16px;}
        .logout-btn:hover{background:rgba(255,80,80,.1);color:#ff7070;border-color:rgba(255,80,80,.25);}

        /* ══ MAIN ══ */
        .main{flex:1;min-width:0;display:flex;flex-direction:column;gap:16px;overflow:hidden;}
        .topbar{display:flex;align-items:center;justify-content:space-between;flex-shrink:0;padding-top:2px;}
        .topbar h1{font-size:1.65rem;font-weight:800;letter-spacing:-.5px;}
        .topbar p{font-size:.83rem;color:var(--text-soft);margin-top:3px;}
        .admin-badge{display:flex;align-items:center;gap:8px;background:rgba(124,111,247,.1);border:1px solid rgba(124,111,247,.2);border-radius:99px;padding:7px 16px 7px 10px;font-size:.82rem;color:var(--accent2);}
        .admin-badge svg{width:14px;height:14px;}
        .content{flex:1;overflow-y:auto;overflow-x:hidden;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.08) transparent;}
        .content::-webkit-scrollbar{width:4px;}
        .content::-webkit-scrollbar-thumb{background:rgba(255,255,255,.08);border-radius:4px;}

        /* shared components */
        .glass-card{background:var(--glass);border:1px solid var(--glass-border);border-radius:var(--radius-lg);backdrop-filter:blur(16px);}
        .glass-card-inner{padding:24px;}
        .card-title{font-size:1rem;font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:10px;}
        .card-title-line{flex:1;height:1px;background:var(--glass-border);}
        .data-table{width:100%;border-collapse:collapse;}
        .data-table th{text-align:left;font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);padding:10px 14px;border-bottom:1px solid var(--glass-border);}
        .data-table td{padding:13px 14px;font-size:.875rem;color:var(--text-soft);border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle;}
        .data-table tr:last-child td{border-bottom:none;}
        .data-table tr:hover td{background:rgba(255,255,255,.02);}
        .btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:var(--radius-sm);font-family:'Outfit',sans-serif;font-size:.8rem;font-weight:600;border:none;cursor:pointer;transition:opacity .15s,transform .12s;text-decoration:none;}
        .btn:hover{opacity:.85;transform:translateY(-1px);}
        .btn svg{width:13px;height:13px;}
        .btn-accent{background:var(--accent);color:#091510;}
        .btn-purple{background:var(--accent2);color:#fff;}
        .btn-danger{background:rgba(255,112,112,.15);color:var(--danger);border:1px solid rgba(255,112,112,.2);}
        .btn-warning{background:rgba(245,158,11,.15);color:var(--accent3);border:1px solid rgba(245,158,11,.2);}
        .btn-success{background:rgba(0,229,160,.12);color:var(--accent);border:1px solid rgba(0,229,160,.2);}
        .btn-ghost{background:var(--glass);color:var(--text-soft);border:1px solid var(--glass-border);}
        .alert{padding:11px 14px;border-radius:var(--radius-sm);font-size:.83rem;margin-bottom:16px;}
        .alert-success{background:rgba(0,229,160,.08);color:var(--accent);border:1px solid rgba(0,229,160,.18);}
        .alert-danger{background:rgba(255,80,80,.08);color:#ff8a8a;border:1px solid rgba(255,80,80,.18);}
        .field{margin-bottom:14px;}
        .field label{display:block;font-size:.7rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px;}
        .field input,.field select,.field textarea{width:100%;padding:10px 14px;font-family:'Outfit',sans-serif;font-size:.875rem;background:rgba(255,255,255,.04);border:1px solid var(--glass-border);border-radius:var(--radius-sm);color:var(--text);outline:none;transition:border-color .2s;}
        .field input::placeholder,.field textarea::placeholder{color:var(--text-muted);}
        .field input:focus,.field select:focus,.field textarea:focus{border-color:rgba(124,111,247,.4);}
        .field select option{background:#0d1f18;}
        .field textarea{resize:vertical;min-height:80px;}
        .modal-overlay{display:none;position:fixed;inset:0;z-index:500;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:20px;}
        .modal-overlay.open{display:flex;}
        .modal{background:#0e2019;border:1px solid var(--glass-border);border-radius:var(--radius-lg);padding:32px;width:100%;max-width:440px;animation:modalIn .25s ease both;}
        @keyframes modalIn{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
        .modal-title{font-size:1.1rem;font-weight:700;margin-bottom:6px;}
        .modal-sub{font-size:.83rem;color:var(--text-soft);margin-bottom:20px;}
        .modal-actions{display:flex;gap:10px;margin-top:20px;}
        .status-badge{display:inline-block;padding:3px 10px;border-radius:99px;font-size:.68rem;font-weight:700;letter-spacing:.06em;}
        .status-active{background:rgba(0,229,160,.12);color:var(--accent);border:1px solid rgba(0,229,160,.2);}
        .status-inactive{background:rgba(255,112,112,.1);color:var(--danger);border:1px solid rgba(255,112,112,.2);}
        .status-pending{background:rgba(245,158,11,.1);color:var(--accent3);border:1px solid rgba(245,158,11,.2);}
        .status-rejected{background:rgba(255,112,112,.1);color:var(--danger);border:1px solid rgba(255,112,112,.2);}
        .empty-state{text-align:center;padding:48px 20px;color:var(--text-muted);font-size:.875rem;}
        .empty-state svg{width:36px;height:36px;margin:0 auto 12px;opacity:.3;display:block;}

        /* search bar */
        .search-bar{display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;}
        .search-input-wrap{position:relative;flex:1;min-width:200px;}
        .search-input-wrap svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--text-muted);pointer-events:none;}
        .search-input{width:100%;padding:9px 14px 9px 36px;font-family:'Outfit',sans-serif;font-size:.875rem;background:rgba(255,255,255,.04);border:1px solid var(--glass-border);border-radius:var(--radius-sm);color:var(--text);outline:none;transition:border-color .2s;}
        .search-input::placeholder{color:var(--text-muted);}
        .search-input:focus{border-color:rgba(124,111,247,.4);}
        .filter-select{padding:9px 14px;font-family:'Outfit',sans-serif;font-size:.875rem;background:rgba(255,255,255,.04);border:1px solid var(--glass-border);border-radius:var(--radius-sm);color:var(--text);outline:none;cursor:pointer;}
        .filter-select option{background:#0d1f18;}

        /* session timeout */
        .timeout-banner{display:none;position:fixed;bottom:20px;right:20px;z-index:9999;background:#0e2019;border:1px solid rgba(245,158,11,.3);border-radius:14px;padding:14px 18px;box-shadow:0 8px 32px rgba(0,0,0,.4);max-width:300px;animation:slideUp .3s ease;}
        @keyframes slideUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .timeout-banner.show{display:block;}
        .timeout-title{font-size:.85rem;font-weight:700;color:#f59e0b;margin-bottom:4px;}
        .timeout-sub{font-size:.75rem;color:var(--text-soft);}
        .timeout-countdown{font-size:1.2rem;font-weight:800;color:#f59e0b;margin:8px 0;}
        .timeout-btn{padding:7px 16px;background:rgba(245,158,11,.15);border:1px solid rgba(245,158,11,.3);border-radius:8px;color:#f59e0b;font-family:'Outfit',sans-serif;font-size:.8rem;font-weight:600;cursor:pointer;transition:background .15s;}
        .timeout-btn:hover{background:rgba(245,158,11,.25);}
    </style>
    @yield('styles')
</head>
<body>
<div class="app">
    @php $pendingCount = \App\Models\User::where('status','pending')->count(); @endphp

    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
            <svg viewBox="0 0 20 20" fill="none"><path d="M10 2L3 6v8l7 4 7-4V6L10 2z" fill="#091510"/><path d="M10 2v12M3 6l7 4 7-4" stroke="#091510" stroke-width="1.5"/></svg>
        </a>
        <nav class="sidebar-nav">
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                <span class="tip">Dashboard</span>
            </a>
            {{-- Spaces --}}
            <a href="{{ route('admin.spaces') }}" class="nav-item {{ request()->routeIs('admin.spaces') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span class="tip">Spaces</span>
            </a>
            {{-- Users --}}
            <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="tip">Users</span>
            </a>
            {{-- Verifications --}}
            <a href="{{ route('admin.verifications') }}" class="nav-item {{ request()->routeIs('admin.verifications') ? 'active' : '' }}" style="position:relative;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                @if($pendingCount > 0)<span class="nav-badge">{{ $pendingCount }}</span>@endif
                <span class="tip">Verifications</span>
            </a>
            {{-- Admins --}}
            <a href="{{ route('admin.admins') }}" class="nav-item {{ request()->routeIs('admin.admins') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span class="tip">Admins</span>
            </a>
            {{-- Activity Logs --}}
            <a href="{{ route('admin.activity-logs') }}" class="nav-item {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span class="tip">Activity Logs</span>
            </a>
        </nav>
        <div class="sidebar-bottom">
            <div class="avatar-wrap">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="submit" class="logout-btn" title="Sign out">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>@yield('page-title')</h1>
                <p>@yield('page-sub')</p>
            </div>
            <div class="admin-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Admin · {{ auth()->user()->name }}
            </div>
        </div>
        <div class="content">
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
            @yield('content')
        </div>
    </main>
</div>

{{-- Session timeout warning --}}
<div class="timeout-banner" id="timeoutBanner">
    <div class="timeout-title">⚠ Session Expiring</div>
    <div class="timeout-sub">You'll be logged out due to inactivity in:</div>
    <div class="timeout-countdown" id="timeoutCountdown">60s</div>
    <button class="timeout-btn" onclick="resetTimer()">Stay Logged In</button>
</div>

<script>
const TIMEOUT = 3600;
const WARN_AT = 60;
let remaining = TIMEOUT;
let warned = false;
let countdown = WARN_AT;
let countdownInterval;

function resetTimer() {
    remaining = TIMEOUT;
    warned = false;
    clearInterval(countdownInterval);
    document.getElementById('timeoutBanner').classList.remove('show');
}

function startCountdown() {
    countdown = WARN_AT;
    document.getElementById('timeoutBanner').classList.add('show');
    countdownInterval = setInterval(() => {
        countdown--;
        document.getElementById('timeoutCountdown').textContent = countdown + 's';
        if (countdown <= 0) {
            clearInterval(countdownInterval);
            document.getElementById('logoutForm').submit();
        }
    }, 1000);
}

setInterval(() => {
    remaining--;
    if (remaining <= WARN_AT && !warned) {
        warned = true;
        startCountdown();
    }
    if (remaining <= 0) {
        document.getElementById('logoutForm').submit();
    }
}, 1000);

['mousemove','keydown','click','scroll','touchstart'].forEach(e => {
    document.addEventListener(e, resetTimer, { passive: true });
});
</script>
@yield('scripts')
</body>
</html>