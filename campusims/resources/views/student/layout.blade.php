<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampuSIMS — @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:#091510;--glass:rgba(255,255,255,.04);--glass-hover:rgba(255,255,255,.07);
            --glass-border:rgba(255,255,255,.08);--accent:#00e5a0;--accent2:#7c6ff7;
            --text:#ddeee6;--text-soft:rgba(221,238,230,.55);--text-muted:rgba(221,238,230,.28);
            --sidebar-w:72px;--radius-lg:22px;--radius-md:14px;--radius-sm:10px;--danger:#ff7070;
        }
        html,body{height:100%;font-family:'Outfit',sans-serif;background:var(--bg);color:var(--text);-webkit-font-smoothing:antialiased;overflow:hidden;}
        body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;background:radial-gradient(ellipse 80% 60% at 5% 0%,rgba(0,229,160,.13) 0%,transparent 55%),radial-gradient(ellipse 50% 50% at 90% 90%,rgba(124,111,247,.10) 0%,transparent 55%),radial-gradient(ellipse 35% 35% at 55% 15%,rgba(200,240,77,.06) 0%,transparent 50%);}
        .app{position:relative;z-index:1;display:flex;height:100vh;padding:14px;gap:14px;}
        .sidebar{width:var(--sidebar-w);background:rgba(255,255,255,.03);border:1px solid var(--glass-border);border-radius:var(--radius-lg);backdrop-filter:blur(24px);display:flex;flex-direction:column;align-items:center;padding:18px 0;flex-shrink:0;}
        .sidebar-logo{width:42px;height:42px;background:linear-gradient(135deg,var(--accent) 0%,#00b87a 100%);border-radius:13px;display:flex;align-items:center;justify-content:center;margin-bottom:30px;text-decoration:none;box-shadow:0 4px 20px rgba(0,229,160,.3);flex-shrink:0;}
        .sidebar-logo svg{width:20px;height:20px;}
        .sidebar-nav{display:flex;flex-direction:column;align-items:center;gap:4px;flex:1;width:100%;padding:0 10px;}
        .nav-item{width:48px;height:48px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;text-decoration:none;color:var(--text-muted);position:relative;transition:background .18s,color .18s;}
        .nav-item svg{width:20px;height:20px;transition:transform .18s;}
        .nav-item:hover{background:var(--glass-hover);color:var(--text-soft);}
        .nav-item:hover svg{transform:scale(1.1);}
        .nav-item.active{background:rgba(0,229,160,.12);color:var(--accent);}
        .nav-item.active::before{content:'';position:absolute;left:-10px;width:3px;height:22px;background:var(--accent);border-radius:0 3px 3px 0;box-shadow:0 0 10px var(--accent);}
        .nav-item .tip{position:absolute;left:58px;background:rgba(9,21,16,.96);border:1px solid var(--glass-border);color:var(--text);font-size:.73rem;font-weight:500;padding:5px 10px;border-radius:8px;white-space:nowrap;opacity:0;pointer-events:none;transform:translateX(-6px);transition:opacity .15s,transform .15s;z-index:999;}
        .nav-item:hover .tip{opacity:1;transform:translateX(0);}
        .sidebar-bottom{display:flex;flex-direction:column;align-items:center;gap:10px;padding:0 10px;}
        .avatar-wrap{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--accent2),#5147c9);display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:700;color:#fff;border:2px solid rgba(124,111,247,.35);box-shadow:0 0 14px rgba(124,111,247,.2);}
        .logout-btn{width:38px;height:38px;border-radius:var(--radius-sm);background:transparent;border:1px solid var(--glass-border);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-muted);transition:background .18s,color .18s,border-color .18s;}
        .logout-btn svg{width:16px;height:16px;}
        .logout-btn:hover{background:rgba(255,80,80,.1);color:#ff7070;border-color:rgba(255,80,80,.25);}
        .main{flex:1;min-width:0;display:flex;flex-direction:column;gap:16px;overflow:hidden;}
        .topbar{display:flex;align-items:center;justify-content:space-between;flex-shrink:0;padding-top:2px;}
        .topbar h1{font-size:1.65rem;font-weight:800;letter-spacing:-.5px;}
        .topbar p{font-size:.83rem;color:var(--text-soft);margin-top:3px;}
        .greeting-pill{display:flex;align-items:center;gap:8px;background:var(--glass);border:1px solid var(--glass-border);border-radius:99px;padding:7px 16px 7px 10px;font-size:.82rem;color:var(--text-soft);backdrop-filter:blur(12px);}
        .pulse{width:8px;height:8px;border-radius:50%;background:var(--accent);box-shadow:0 0 0 0 rgba(0,229,160,.5);animation:pulse 2s infinite;}
        @keyframes pulse{0%{box-shadow:0 0 0 0 rgba(0,229,160,.5)}70%{box-shadow:0 0 0 6px rgba(0,229,160,0)}100%{box-shadow:0 0 0 0 rgba(0,229,160,0)}}
        .content{flex:1;overflow-y:auto;overflow-x:hidden;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.08) transparent;}
        .content::-webkit-scrollbar{width:4px;}
        .content::-webkit-scrollbar-thumb{background:rgba(255,255,255,.08);border-radius:4px;}
        .glass-card{background:var(--glass);border:1px solid var(--glass-border);border-radius:var(--radius-lg);backdrop-filter:blur(16px);}
        .alert{padding:12px 16px;border-radius:var(--radius-md);font-size:.875rem;margin-bottom:16px;}
        .alert-success{background:rgba(0,229,160,.08);color:var(--accent);border:1px solid rgba(0,229,160,.18);}
        .alert-danger{background:rgba(255,80,80,.08);color:#ff8a8a;border:1px solid rgba(255,80,80,.18);}
        .field{margin-bottom:18px;}
        .field label{display:block;font-size:.72rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:7px;}
        .field input{width:100%;padding:12px 16px;font-family:'Outfit',sans-serif;font-size:.9rem;background:rgba(255,255,255,.04);border:1px solid var(--glass-border);border-radius:var(--radius-md);color:var(--text);outline:none;transition:border-color .2s,background .2s;}
        .field input::placeholder{color:var(--text-muted);}
        .field input:focus{border-color:rgba(0,229,160,.4);background:rgba(0,229,160,.04);}
        .field input.is-invalid{border-color:rgba(255,80,80,.5);}
        .invalid-feedback{font-size:.78rem;color:#ff8a8a;margin-top:5px;}

        /* session timeout warning */
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
    <aside class="sidebar">
        <a href="{{ route('student.dashboard') }}" class="sidebar-logo">
            <svg viewBox="0 0 20 20" fill="none"><path d="M10 2L3 6v8l7 4 7-4V6L10 2z" fill="#091510"/><path d="M10 2v12M3 6l7 4 7-4" stroke="#091510" stroke-width="1.5"/></svg>
        </a>
        <nav class="sidebar-nav">
            <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                <span class="tip">Dashboard</span>
            </a>
            <a href="{{ route('student.map') }}" class="nav-item {{ request()->routeIs('student.map') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                <span class="tip">Campus Map</span>
            </a>
            <a href="{{ route('student.id-card') }}" class="nav-item {{ request()->routeIs('student.id-card') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8" cy="12" r="2.5"/><line x1="13" y1="10" x2="19" y2="10"/><line x1="13" y1="14" x2="17" y2="14"/></svg>
                <span class="tip">My ID Card</span>
            </a>
            <a href="{{ route('student.settings') }}" class="nav-item {{ request()->routeIs('student.settings') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span class="tip">Settings</span>
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
            <div class="greeting-pill">
                <span class="pulse"></span>
                Hi, {{ explode(' ', auth()->user()->name)[0] }}!
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
// Session timeout: 1 hour (3600s), warn at 60s remaining
const TIMEOUT    = 3600;
const WARN_AT    = 60;
let   remaining  = TIMEOUT;
let   warned     = false;
let   countdown  = WARN_AT;
let   countdownInterval;

function resetTimer() {
    remaining = TIMEOUT;
    warned    = false;
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

// Reset on any user activity
['mousemove','keydown','click','scroll','touchstart'].forEach(e => {
    document.addEventListener(e, resetTimer, { passive: true });
});
</script>
@yield('scripts')
</body>
</html>