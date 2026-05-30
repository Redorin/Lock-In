<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>UDDSafeSpaces — Focus Mode</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Essential tokens from main layout */
        :root { --ease: cubic-bezier(.22,1,.36,1); --t: .22s; }
        [data-theme="dark"] {
            --bg: #0b1120; --surface: rgba(30, 41, 59, 0.4); --surface2: rgba(30, 41, 59, 0.7);
            --border: rgba(255,255,255,.05); --border2: rgba(255,255,255,.1);
            --text: #f1f5f9; --text-soft: #94a3b8; --text-muted: #475569;
            --accent: #3b82f6; --accent2: #60a5fa; --accent-bg: rgba(59,130,246,.1); --accent-glow: rgba(59,130,246,.3);
            --danger: #f87171; --danger-bg: rgba(248,113,113,.08); --danger-border: rgba(248,113,113,.2);
            --success-bg: rgba(74,222,128,.08);
            --inset: inset 0 1px 0 rgba(255,255,255,.06); --shadow-lg: 0 20px 60px rgba(0,0,0,.5);
        }
        [data-theme="light"] {
            --bg: #f0f4f8; --surface: #ffffff; --surface2: #f8fafc;
            --border: rgba(0,0,0,.07); --border2: rgba(0,0,0,.12);
            --text: #0f172a; --text-soft: #475569; --text-muted: #94a3b8;
            --accent: #2563eb; --accent2: #1d4ed8; --accent-bg: rgba(37,99,235,.07); --accent-glow: rgba(37,99,235,.15);
            --danger: #dc2626; --danger-bg: rgba(220,38,38,.07); --danger-border: rgba(220,38,38,.18);
            --success-bg: rgba(22,163,74,.07);
            --inset: inset 0 1px 0 rgba(255,255,255,.8); --shadow-lg: 0 20px 48px rgba(0,0,0,.1);
        }

        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
        html { background:var(--bg); }
        body {
            font-family:'Inter',sans-serif; background:var(--bg); color:var(--text);
            min-height: 100vh; display: flex; flex-direction: column;
            align-items: center; justify-content: center; padding: 24px;
        }

        /* Ambient mesh background */
        body::before {
            content:''; position:fixed;inset:0;z-index:-1;pointer-events:none;
            background:
                radial-gradient(ellipse 60% 50% at 10% 10%, var(--accent-bg) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 90% 80%, rgba(139,92,246,.06) 0%, transparent 60%),
                radial-gradient(ellipse 30% 30% at 50% 50%, var(--accent-bg) 0%, transparent 70%);
        }

        .focus-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 32px; padding: 48px 32px; max-width: 440px; width: 100%;
            text-align: center; backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            box-shadow: var(--shadow-lg), var(--inset);
            animation: slideUp .5s var(--ease) both;
        }
        @keyframes slideUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }

        .pin-icon {
            width: 72px; height: 72px; margin: 0 auto 24px;
            background: var(--accent-bg); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--accent2); box-shadow: 0 0 30px var(--accent-glow);
            animation: pulse-pin 3s infinite var(--ease);
        }
        @keyframes pulse-pin {
            0% { box-shadow: 0 0 0 0 var(--accent-glow); }
            50% { box-shadow: 0 0 0 20px transparent; }
            100% { box-shadow: 0 0 0 0 transparent; }
        }
        .pin-icon svg { width: 32px; height: 32px; }

        .status-badge {
            display: inline-block; background: var(--surface2); border: 1px solid var(--border2);
            padding: 6px 16px; border-radius: 99px; font-size: .75rem; font-weight: 700;
            letter-spacing: .08em; text-transform: uppercase; color: var(--accent2);
            margin-bottom: 16px;
        }

        .space-title {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800;
            letter-spacing: -.5px; color: var(--text); line-height: 1.2; margin-bottom: 8px;
        }
        .space-building { font-size: 1.1rem; color: var(--text-soft); font-weight: 500; margin-bottom: 32px; }

        .time-box {
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 16px; padding: 20px; display: grid; grid-template-columns: repeat(2,1fr);
            gap: 14px;
            margin-bottom: 32px;
        }
        .time-item { display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .time-label { font-size: .75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }
        .time-val { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.1rem; font-weight: 700; color: var(--text); }
        .countdown-box {
            background: var(--accent-bg); border: 1px solid rgba(59,130,246,.22);
            border-radius: 18px; padding: 18px;
            margin-bottom: 24px; text-align: center;
        }
        .countdown-label { font-size:.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--accent2);margin-bottom:6px; }
        .countdown-val { font-family:'Plus Jakarta Sans',sans-serif;font-size:2rem;font-weight:800;color:var(--text);letter-spacing:-.5px; }
        .countdown-sub { font-size:.8rem;color:var(--text-soft);margin-top:4px; }

        .btn-checkout {
            width: 100%; padding: 18px; border-radius: 99px; border: none;
            background: var(--danger-bg); border: 1px solid var(--danger-border);
            color: var(--danger); font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.05rem; font-weight: 800; cursor: pointer;
            transition: all var(--t) var(--ease); box-shadow: 0 4px 12px rgba(248,113,113,.1);
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-checkout:hover { background: var(--danger); color: #fff; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(248,113,113,.3); }
        .btn-checkout svg { width: 20px; height: 20px; }
        .checkout-form { margin:0; }
        
        .alert {
            background: var(--success-bg); border: 1px solid rgba(74,222,128,.2);
            color: #4ade80; padding: 14px 20px; border-radius: 16px; margin-bottom: 24px;
            font-size: .9rem; font-weight: 500; text-align: center;
            animation: slideDown .4s var(--ease);
        }
        @keyframes slideDown { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
        @media(max-width:640px) {
            body { justify-content:flex-start; padding:24px 16px 120px; }
            .focus-card { padding:36px 22px; border-radius:26px; }
            .time-box { grid-template-columns:1fr; }
            .checkout-form {
                position:fixed; left:16px; right:16px; bottom:calc(16px + env(safe-area-inset-bottom, 0px));
                z-index:20;
            }
            .checkout-form .btn-checkout { box-shadow:0 12px 34px rgba(248,113,113,.28); }
        }
    </style>
</head>
<body>
    <script>
        // Apply theme from local storage if previously set
        (function(){
            const t = localStorage.getItem('uddss_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="focus-card">
        <div class="pin-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
        </div>

        <div class="status-badge">Focus Mode</div>
        <h1 class="space-title">{{ $activeCheckIn->space->name }}</h1>
        <div class="space-building">{{ $activeCheckIn->space->building }}</div>

        <div class="time-box">
            <div class="time-item">
                <span class="time-label">Checked In</span>
                <span class="time-val">{{ $activeCheckIn->checked_in_at->format('g:i A') }}</span>
            </div>
            <div class="time-item">
                <span class="time-label">Duration</span>
                <span class="time-val" id="durationVal">--</span>
            </div>
            <div class="time-item">
                <span class="time-label">Auto-Checkout</span>
                <span class="time-val">{{ $activeCheckIn->checked_in_at->copy()->addHours(2)->format('g:i A') }}</span>
            </div>
        </div>

        <div class="countdown-box">
            <div class="countdown-label">Auto-checkout in</div>
            <div class="countdown-val" id="countdownVal">--</div>
            <div class="countdown-sub">You will be checked out automatically after 2 hours.</div>
        </div>

        <form method="POST" action="{{ route('checkin.checkout') }}" class="checkout-form">
            @csrf
            <button type="submit" class="btn-checkout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                Check Out Now
            </button>
        </form>
    </div>
    <script>
        const checkedInAt = new Date(@json($activeCheckIn->checked_in_at->toIso8601String()));
        const checkoutAt = new Date(checkedInAt.getTime() + (2 * 60 * 60 * 1000));

        function formatDuration(ms) {
            const totalSeconds = Math.max(0, Math.floor(ms / 1000));
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            if (hours > 0) return `${hours}h ${minutes}m`;
            return `${minutes}m ${seconds}s`;
        }

        function tickFocusTimers() {
            const now = new Date();
            document.getElementById('durationVal').textContent = formatDuration(now - checkedInAt);
            document.getElementById('countdownVal').textContent = formatDuration(checkoutAt - now);
        }

        tickFocusTimers();
        setInterval(tickFocusTimers, 1000);
    </script>
</body>
</html>
