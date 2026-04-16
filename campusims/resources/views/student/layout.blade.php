<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>UDDSafeSpaces — @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ═══════════════════════════════════════════
           THEME TOKENS
        ═══════════════════════════════════════════ */
        :root {
            --ease: cubic-bezier(.22,1,.36,1);
            --ease-back: cubic-bezier(.34,1.56,.64,1);
            --t: .22s;
        }
        [data-theme="dark"] {
            --bg: #0b1120;
            --bg2: #0f172a;
            --surface: rgba(30, 41, 59, 0.4);
            --surface2: rgba(30, 41, 59, 0.7);
            --surface3: rgba(51, 65, 85, 0.5);
            --border: rgba(255,255,255,.05);
            --border2: rgba(255,255,255,.1);
            --text: #f1f5f9;
            --text-soft: #94a3b8;
            --text-muted: #475569;
            --accent: #3b82f6;
            --accent2: #60a5fa;
            --accent3: #bfdbfe;
            --accent-bg: rgba(59,130,246,.1);
            --accent-border: rgba(59,130,246,.25);
            --accent-glow: rgba(59,130,246,.3);
            --danger: #f87171;
            --danger-bg: rgba(248,113,113,.08);
            --danger-border: rgba(248,113,113,.2);
            --warn: #fbbf24;
            --warn-bg: rgba(251,191,36,.08);
            --warn-border: rgba(251,191,36,.2);
            --success: #4ade80;
            --success-bg: rgba(74,222,128,.08);
            --sidebar: rgba(15, 23, 42, 0.6);
            --drawer: #0f172a;
            --modal: rgba(15, 23, 42, 0.85);
            --overlay: rgba(0,0,0,.7);
            --inset: inset 0 1px 0 rgba(255,255,255,.06);
            --shadow-sm: 0 2px 8px rgba(0,0,0,.3);
            --shadow-md: 0 8px 32px rgba(0,0,0,.4);
            --shadow-lg: 0 20px 60px rgba(0,0,0,.5);
        }
        [data-theme="light"] {
            --bg: #f0f4f8;
            --bg2: #e8edf5;
            --surface: #ffffff;
            --surface2: #f8fafc;
            --surface3: #f1f5f9;
            --border: rgba(0,0,0,.07);
            --border2: rgba(0,0,0,.12);
            --text: #0f172a;
            --text-soft: #475569;
            --text-muted: #94a3b8;
            --accent: #2563eb;
            --accent2: #1d4ed8;
            --accent3: #1e3a8a;
            --accent-bg: rgba(37,99,235,.07);
            --accent-border: rgba(37,99,235,.18);
            --accent-glow: rgba(37,99,235,.15);
            --danger: #dc2626;
            --danger-bg: rgba(220,38,38,.07);
            --danger-border: rgba(220,38,38,.18);
            --warn: #d97706;
            --warn-bg: rgba(217,119,6,.08);
            --warn-border: rgba(217,119,6,.2);
            --success: #16a34a;
            --success-bg: rgba(22,163,74,.07);
            --sidebar: rgba(255,255,255,.92);
            --drawer: #ffffff;
            --modal: #ffffff;
            --overlay: rgba(0,0,0,.45);
            --inset: inset 0 1px 0 rgba(255,255,255,.8);
            --shadow-sm: 0 2px 8px rgba(0,0,0,.06);
            --shadow-md: 0 8px 24px rgba(0,0,0,.08);
            --shadow-lg: 0 20px 48px rgba(0,0,0,.1);
        }

        /* ═══════════════════════════════════════════
           BASE
        ═══════════════════════════════════════════ */
        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
        html { background:var(--bg); scroll-behavior:smooth; }
        body {
            font-family:'Inter',sans-serif;
            background:var(--bg);
            color:var(--text);
            -webkit-font-smoothing:antialiased;
            transition:background var(--t) var(--ease), color var(--t) var(--ease);
        }

        /* Ambient mesh background */
        body::before {
            content:'';
            position:fixed;inset:0;z-index:0;pointer-events:none;
            background:
                radial-gradient(ellipse 60% 50% at 10% 10%, var(--accent-bg) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 90% 80%, rgba(139,92,246,.06) 0%, transparent 60%),
                radial-gradient(ellipse 30% 30% at 50% 50%, var(--accent-bg) 0%, transparent 70%);
            transition:opacity var(--t) var(--ease);
        }

        /* ═══════════════════════════════════════════
           DESKTOP SIDEBAR
        ═══════════════════════════════════════════ */
        .desktop-sidebar {
            position:fixed;top:24px;left:24px;bottom:24px;width:78px;z-index:100;
            background:var(--sidebar);
            border:1px solid var(--border);
            border-radius:28px;
            backdrop-filter:blur(32px) saturate(180%);
            -webkit-backdrop-filter:blur(32px) saturate(180%);
            display:flex;flex-direction:column;align-items:center;
            padding:24px 0;
            transition:background var(--t) var(--ease), border-color var(--t) var(--ease);
            box-shadow:var(--shadow-lg), var(--inset);
        }

        .s-logo {
            width:44px;height:44px;border-radius:14px;
            overflow:hidden;
            display:flex;align-items:center;justify-content:center;
            margin-bottom:32px;text-decoration:none;
            flex-shrink:0;
            box-shadow:0 4px 16px var(--accent-glow);
            transition:transform var(--t) var(--ease-back), box-shadow var(--t) var(--ease);
        }
        .s-logo:hover { transform:scale(1.08); box-shadow:0 6px 24px var(--accent-glow); }
        .s-logo img { width:100%;height:100%;object-fit:cover; }

        .s-nav { display:flex;flex-direction:column;align-items:center;gap:2px;flex:1;width:100%;padding:0 10px; }

        .ni {
            width:48px;height:48px;border-radius:50%; /* fully rounded */
            display:flex;align-items:center;justify-content:center;
            text-decoration:none;color:var(--text-muted);
            position:relative;
            transition:background var(--t) var(--ease), color var(--t) var(--ease), transform var(--t) var(--ease-back);
        }
        .ni svg { width:20px;height:20px; transition:transform var(--t) var(--ease-back); }
        .ni:hover { background:var(--surface2); color:var(--text-soft); transform:scale(1.06); }
        .ni:hover svg { transform:scale(1.1); }
        .ni.active { background:var(--surface2); color:var(--accent2); box-shadow:var(--inset); }
        .ni.active::before {
            content:'';position:absolute;left:-8px;
            width:4px;height:24px;background:var(--accent);
            border-radius:99px;
            box-shadow:0 0 12px var(--accent-glow);
        }
        .ni .tip {
            position:absolute;left:56px;
            background:var(--modal);border:1px solid var(--border2);
            color:var(--text);font-family:'Plus Jakarta Sans',sans-serif;
            font-size:.72rem;font-weight:600;
            padding:5px 10px;border-radius:8px;
            white-space:nowrap;
            opacity:0;pointer-events:none;
            transform:translateX(-4px);
            transition:opacity .15s, transform .15s;
            z-index:999;
            box-shadow:var(--shadow-sm);
        }
        .ni:hover .tip { opacity:1;transform:translateX(0); }

        .nbadge {
            position:absolute;top:6px;right:5px;
            min-width:16px;height:16px;border-radius:99px;
            background:var(--warn);color:#000;
            font-size:.55rem;font-weight:800;
            display:flex;align-items:center;justify-content:center;
            padding:0 3px;
            animation:badgePop .3s var(--ease-back) both;
        }
        @keyframes badgePop { from{transform:scale(0)} to{transform:scale(1)} }

        .s-bot { display:flex;flex-direction:column;align-items:center;gap:8px;padding:0 10px; }

        .theme-toggle {
            width:38px;height:38px;border-radius:10px;
            background:var(--surface2);border:1px solid var(--border);
            display:flex;align-items:center;justify-content:center;
            cursor:pointer;color:var(--text-soft);
            transition:all var(--t) var(--ease);
        }
        .theme-toggle svg { width:16px;height:16px; transition:transform .4s var(--ease); }
        .theme-toggle:hover { background:var(--accent-bg);color:var(--accent2);border-color:var(--accent-border); }
        .theme-toggle:active svg { transform:rotate(30deg) scale(1.2); }
        .icon-sun { display:none; }
        .icon-moon { display:block; }
        [data-theme="light"] .icon-sun { display:block; }
        [data-theme="light"] .icon-moon { display:none; }

        .s-av {
            width:36px;height:36px;border-radius:50%;
            background:linear-gradient(135deg, var(--accent), #6366f1);
            display:flex;align-items:center;justify-content:center;
            font-family:'Plus Jakarta Sans',sans-serif;
            font-size:.8rem;font-weight:800;color:#fff;
            border:2px solid var(--accent-border);
            box-shadow:0 0 0 0 var(--accent-glow);
            transition:box-shadow var(--t) var(--ease);
            cursor:pointer;
        }
        .s-av:hover { box-shadow:0 0 0 4px var(--accent-glow); }

        .s-lo {
            width:36px;height:36px;border-radius:10px;
            background:transparent;border:1px solid var(--border);
            display:flex;align-items:center;justify-content:center;
            cursor:pointer;color:var(--text-muted);
            transition:all var(--t) var(--ease);
        }
        .s-lo svg { width:15px;height:15px; }
        .s-lo:hover { background:var(--danger-bg);color:var(--danger);border-color:var(--danger-border); }

        /* ═══════════════════════════════════════════
           DESKTOP MAIN
        ═══════════════════════════════════════════ */
        .desktop-main { margin-left:110px;min-height:100vh;position:relative;z-index:1; }
        .desktop-topbar {
            display:flex;align-items:center;justify-content:flex-end; /* Right align the greeting */
            padding:24px 32px 0;gap:16px;flex-wrap:wrap;
        }
        .topbar-right { display:flex;align-items:center;gap:12px; }
        .gpill {
            display:flex;align-items:center;gap:12px;
            background:var(--surface);border:1px solid var(--border);
            border-radius:99px;padding:6px 16px 6px 6px;
            font-size:.9rem;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;color:var(--text);
            box-shadow:var(--shadow-sm), var(--inset);
            transition:all var(--t) var(--ease);
        }
        .gpill:hover { border-color:var(--accent-border); }
        .gpill .g-av {
            width:34px;height:34px;border-radius:50%;
            background:linear-gradient(135deg, var(--accent), #6366f1);
            display:flex;align-items:center;justify-content:center;
            font-size:.7rem;font-weight:800;color:#fff;
        }
        .pdot {
            width:7px;height:7px;border-radius:50%;background:var(--accent);
            box-shadow:0 0 0 0 var(--accent-glow);
            animation:pulse 2.5s ease infinite;
        }
        @keyframes pulse {
            0%   { box-shadow:0 0 0 0 var(--accent-glow); }
            50%  { box-shadow:0 0 0 5px transparent; }
            100% { box-shadow:0 0 0 0 transparent; }
        }
        .desktop-content { padding:20px 32px 48px; }

        /* ═══════════════════════════════════════════
           MOBILE
        ═══════════════════════════════════════════ */
        .mobile-topbar {
            display:none;
            align-items:center;justify-content:space-between;
            padding:12px 16px;
            background:var(--sidebar);
            border-bottom:1px solid var(--border);
            position:sticky;top:0;z-index:50;
            backdrop-filter:blur(24px) saturate(180%);
            -webkit-backdrop-filter:blur(24px) saturate(180%);
            transition:background var(--t) var(--ease);
        }
        .mob-brand { display:flex;align-items:center;gap:10px;text-decoration:none; }
        .mob-logo { width:34px;height:34px;border-radius:9px;overflow:hidden;flex-shrink:0;box-shadow:0 2px 8px var(--accent-glow); }
        .mob-logo img { width:100%;height:100%;object-fit:cover;display:block; }
        .mob-name { font-family:'Plus Jakarta Sans',sans-serif;font-size:.97rem;font-weight:800;letter-spacing:-.3px;color:var(--text); }
        .mob-right { display:flex;align-items:center;gap:8px; }
        .mob-theme {
            width:36px;height:36px;background:var(--surface2);border:1px solid var(--border);
            border-radius:9px;display:flex;align-items:center;justify-content:center;
            cursor:pointer;color:var(--text-soft);
            transition:all var(--t) var(--ease);
        }
        .mob-theme:hover { background:var(--accent-bg);color:var(--accent2);border-color:var(--accent-border); }
        .mob-theme svg { width:17px;height:17px; }

        /* Greeting pill (mobile topbar — display only, not tappable) */
        .mob-gpill {
            display:flex;align-items:center;gap:8px;
            background:var(--surface2);border:1px solid var(--border2);
            border-radius:99px;padding:5px 12px 5px 5px;
            font-family:'Plus Jakarta Sans',sans-serif;font-size:.8rem;font-weight:700;color:var(--text);
            box-shadow:var(--shadow-sm);
        }
        .mob-gpill .g-av {
            width:26px;height:26px;border-radius:50%;
            background:linear-gradient(135deg,var(--accent),#6366f1);
            display:flex;align-items:center;justify-content:center;
            font-size:.6rem;font-weight:800;color:#fff;
        }

        .mobile-content { display:none;padding:16px 16px 90px;position:relative;z-index:1; }
        .mob-page-hdr { margin-bottom:20px; }
        .mob-page-hdr h2 { font-family:'Plus Jakarta Sans',sans-serif;font-size:1.4rem;font-weight:800;letter-spacing:-.4px;color:var(--text); }
        .mob-page-hdr p { font-size:.8rem;color:var(--text-soft);margin-top:3px; }

        /* ═══════════════════════════════════════════
           BOTTOM TAB BAR
        ═══════════════════════════════════════════ */
        .mob-tabbar {
            display:none;
            position:fixed;bottom:0;left:0;right:0;z-index:100;
            background:var(--sidebar);
            border-top:1px solid var(--border);
            backdrop-filter:blur(28px) saturate(180%);
            -webkit-backdrop-filter:blur(28px) saturate(180%);
            padding-bottom:env(safe-area-inset-bottom, 0px);
            transition:background var(--t) var(--ease);
            box-shadow:0 -4px 24px rgba(0,0,0,.18);
        }
        .mob-tabbar-inner {
            display:flex;align-items:stretch;
            height:60px;
        }
        .tab-btn {
            flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;
            gap:4px;text-decoration:none;
            color:var(--text-muted);
            transition:color var(--t) var(--ease),background var(--t) var(--ease);
            position:relative;padding:0 4px;
            -webkit-tap-highlight-color:transparent;
        }
        .tab-btn svg { width:20px;height:20px;flex-shrink:0;transition:transform var(--t) var(--ease-back); }
        .tab-btn span { font-family:'Plus Jakarta Sans',sans-serif;font-size:.58rem;font-weight:700;letter-spacing:.02em;line-height:1; }
        .tab-btn:hover { color:var(--text-soft); }
        .tab-btn:hover svg { transform:scale(1.1); }
        .tab-btn.active { color:var(--accent2); }
        .tab-btn.active svg { transform:scale(1.08); }
        .tab-btn.active::before {
            content:'';
            position:absolute;top:0;left:50%;transform:translateX(-50%);
            width:28px;height:2px;
            background:var(--accent);border-radius:0 0 4px 4px;
            box-shadow:0 0 8px var(--accent-glow);
        }

        /* Floating scanner tab (centre, elevated) */
        .tab-scan {
            flex:0 0 72px;display:flex;flex-direction:column;align-items:center;justify-content:center;
            gap:4px;text-decoration:none;
            position:relative;
            margin-top:-20px;
            -webkit-tap-highlight-color:transparent;
        }
        .tab-scan-circle {
            width:54px;height:54px;border-radius:50%;
            background:linear-gradient(135deg,var(--accent),#6366f1);
            display:flex;align-items:center;justify-content:center;
            box-shadow:0 4px 20px var(--accent-glow),var(--inset);
            border:2px solid var(--accent-border);
            transition:all var(--t) var(--ease-back);
        }
        .tab-scan-circle svg { width:22px;height:22px;color:#fff; }
        .tab-scan span { font-family:'Plus Jakarta Sans',sans-serif;font-size:.58rem;font-weight:700;letter-spacing:.02em;color:var(--accent2);margin-top:2px; }
        .tab-scan:active .tab-scan-circle { transform:scale(.92); }
        .tab-scan.active .tab-scan-circle { box-shadow:0 6px 28px var(--accent-glow),var(--inset); }

        /* ═══════════════════════════════════════════
           DRAWER
        ═══════════════════════════════════════════ */
        .drawer-overlay {
            display:none;position:fixed;inset:0;z-index:200;
            background:var(--overlay);
            backdrop-filter:blur(4px);
            animation:fadeIn .2s ease both;
        }
        .drawer-overlay.open { display:block; }
        @keyframes fadeIn { from{opacity:0}to{opacity:1} }

        .drawer {
            position:fixed;top:0;left:-300px;bottom:0;width:300px;z-index:201;
            background:var(--drawer);
            border-right:1px solid var(--border2);
            transition:left .3s var(--ease);
            display:flex;flex-direction:column;overflow:hidden;
            box-shadow:var(--shadow-lg);
        }
        .drawer.open { left:0; }

        .d-head { display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:1px solid var(--border); }
        .d-brand { display:flex;align-items:center;gap:10px; }
        .d-logo { width:36px;height:36px;border-radius:10px;overflow:hidden;flex-shrink:0; }
        .d-logo img { width:100%;height:100%;object-fit:cover;display:block; }
        .d-brand-name { font-family:'Plus Jakarta Sans',sans-serif;font-size:.95rem;font-weight:800;color:var(--text);letter-spacing:-.3px; }
        .d-close {
            width:30px;height:30px;background:var(--surface2);border:1px solid var(--border);
            border-radius:7px;display:flex;align-items:center;justify-content:center;
            cursor:pointer;color:var(--text-soft);
            transition:all var(--t) var(--ease);
        }
        .d-close:hover { background:var(--danger-bg);color:var(--danger); }
        .d-close svg { width:15px;height:15px; }

        .d-user { display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border); }
        .d-av {
            width:42px;height:42px;border-radius:50%;flex-shrink:0;
            background:linear-gradient(135deg, var(--accent), #6366f1);
            display:flex;align-items:center;justify-content:center;
            font-family:'Plus Jakarta Sans',sans-serif;font-size:.95rem;font-weight:800;color:#fff;
            border:2px solid var(--accent-border);
        }
        .d-uname { font-size:.9rem;font-weight:600;color:var(--text); }
        .d-urole { font-size:.72rem;color:var(--text-muted);text-transform:capitalize; }

        .d-nav { flex:1;padding:10px;overflow-y:auto; }
        .d-ni {
            display:flex;align-items:center;gap:12px;
            padding:11px 14px;border-radius:10px;
            text-decoration:none;color:var(--text-soft);
            font-family:'Plus Jakarta Sans',sans-serif;font-size:.88rem;font-weight:600;
            transition:background var(--t) var(--ease), color var(--t) var(--ease), transform var(--t) var(--ease-back);
            margin-bottom:2px;
        }
        .d-ni svg { width:18px;height:18px;flex-shrink:0;opacity:.7; }
        .d-ni:hover { background:var(--surface2);color:var(--text);transform:translateX(3px); }
        .d-ni.active { background:var(--accent-bg);color:var(--accent2); }
        .d-ni.active svg { opacity:1; }

        .d-foot { padding:14px 20px;border-top:1px solid var(--border); }
        .d-theme-btn {
            display:flex;align-items:center;gap:10px;width:100%;
            padding:10px 14px;background:var(--surface2);border:1px solid var(--border);
            border-radius:10px;color:var(--text-soft);
            font-family:'Plus Jakarta Sans',sans-serif;font-size:.85rem;font-weight:600;
            cursor:pointer;margin-bottom:10px;
            transition:all var(--t) var(--ease);
        }
        .d-theme-btn:hover { background:var(--accent-bg);color:var(--accent2);border-color:var(--accent-border); }
        .d-theme-btn svg { width:17px;height:17px; }
        .d-logout {
            display:flex;align-items:center;gap:10px;width:100%;
            padding:10px 14px;background:var(--danger-bg);border:1px solid var(--danger-border);
            border-radius:10px;color:var(--danger);
            font-family:'Plus Jakarta Sans',sans-serif;font-size:.875rem;font-weight:600;cursor:pointer;
            transition:all var(--t) var(--ease);
        }
        .d-logout:hover { background:rgba(220,38,38,.14); }
        .d-logout svg { width:17px;height:17px; }

        /* ═══════════════════════════════════════════
           SHARED COMPONENTS
        ═══════════════════════════════════════════ */
        .gc {
            background:var(--surface);border:1px solid var(--border);
            border-radius:20px;
            backdrop-filter:blur(12px);
            box-shadow:var(--shadow-sm),var(--inset);
            transition:background var(--t) var(--ease), border-color var(--t) var(--ease);
        }

        /* Alerts */
        .alert { padding:12px 16px;border-radius:12px;font-size:.85rem;margin-bottom:16px;display:flex;align-items:center;gap:10px; }
        .alert svg { width:16px;height:16px;flex-shrink:0; }
        .alert-success { background:var(--accent-bg);color:var(--accent2);border:1px solid var(--accent-border); }
        .alert-danger  { background:var(--danger-bg);color:var(--danger);border:1px solid var(--danger-border); }

        /* Fields */
        .field { margin-bottom:16px; }
        .field label { display:block;font-size:.71rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px; }
        .field input,.field select,.field textarea {
            width:100%;padding:11px 14px;
            font-family:'Inter',sans-serif;font-size:.9rem;
            background:var(--surface2);border:1.5px solid var(--border2);
            border-radius:10px;color:var(--text);outline:none;
            transition:border-color var(--t) var(--ease), box-shadow var(--t) var(--ease), background var(--t) var(--ease);
        }
        .field input::placeholder,.field textarea::placeholder { color:var(--text-muted); }
        .field input:focus,.field select:focus,.field textarea:focus {
            border-color:var(--accent);background:var(--surface);
            box-shadow:0 0 0 3px var(--accent-bg);
        }
        .field input.is-invalid { border-color:var(--danger); }
        .invalid-feedback { font-size:.77rem;color:var(--danger);margin-top:4px; }
        .field select option { background:var(--modal);color:var(--text); }
        .field textarea { resize:vertical;min-height:80px; }

        /* Modals */
        .modal-overlay {
            display:none;position:fixed;inset:0;z-index:500;
            background:var(--overlay);backdrop-filter:blur(8px);
            align-items:center;justify-content:center;padding:16px;
        }
        .modal-overlay.open { display:flex; }
        .modal {
            background:var(--modal);border:1px solid var(--border2);
            border-radius:20px;padding:28px;width:100%;max-width:440px;
            animation:modalIn .3s var(--ease) both;
            box-shadow:var(--shadow-lg),var(--inset);
            max-height:90vh;overflow-y:auto;
        }
        @keyframes modalIn { from{opacity:0;transform:translateY(20px) scale(.96)}to{opacity:1;transform:translateY(0) scale(1)} }
        .modal-title { font-family:'Plus Jakarta Sans',sans-serif;font-size:1.1rem;font-weight:800;margin-bottom:6px;color:var(--text); }
        .modal-sub { font-size:.83rem;color:var(--text-soft);margin-bottom:20px; }
        .modal-actions { display:flex;gap:10px;margin-top:20px;flex-wrap:wrap; }
        .modal-actions .btn { flex:1;justify-content:center; }

        /* Badges */
        .sbadge { display:inline-block;padding:3px 9px;border-radius:99px;font-size:.64rem;font-weight:700;letter-spacing:.05em; }
        .sa { background:var(--accent-bg);color:var(--accent2);border:1px solid var(--accent-border); }
        .si { background:var(--danger-bg);color:var(--danger);border:1px solid var(--danger-border); }
        .sp { background:var(--warn-bg);color:var(--warn);border:1px solid var(--warn-border); }

        /* Buttons */
        .btn {
            display:inline-flex;align-items:center;gap:6px;
            padding:8px 14px;border-radius:10px;
            font-family:'Plus Jakarta Sans',sans-serif;font-size:.8rem;font-weight:700;
            border:none;cursor:pointer;
            transition:opacity var(--t) var(--ease), transform var(--t) var(--ease-back), box-shadow var(--t) var(--ease);
            text-decoration:none;white-space:nowrap;
        }
        .btn:hover { opacity:.88;transform:translateY(-1px); }
        .btn:active { transform:translateY(0) scale(.98); }
        .btn svg { width:13px;height:13px; }
        .btn-blue { background:linear-gradient(135deg,var(--accent),#6366f1);color:#fff;box-shadow:0 2px 12px var(--accent-glow); }
        .btn-blue:hover { box-shadow:0 4px 20px var(--accent-glow); }
        .btn-danger { background:var(--danger-bg);color:var(--danger);border:1px solid var(--danger-border); }
        .btn-warn   { background:var(--warn-bg);color:var(--warn);border:1px solid var(--warn-border); }
        .btn-success{ background:var(--success-bg);color:var(--success);border:1px solid rgba(74,222,128,.2); }
        .btn-ghost  { background:var(--surface2);color:var(--text-soft);border:1px solid var(--border2); }

        /* Tables */
        .table-wrap { overflow-x:auto;-webkit-overflow-scrolling:touch; }
        .dt { width:100%;border-collapse:collapse;min-width:480px; }
        .dt th { text-align:left;font-size:.64rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);padding:10px 14px;border-bottom:1px solid var(--border); }
        .dt td { padding:13px 14px;font-size:.85rem;color:var(--text-soft);border-bottom:1px solid var(--border);vertical-align:middle;transition:background var(--t) var(--ease); }
        .dt tr:last-child td { border-bottom:none; }
        .dt tr:hover td { background:var(--surface2); }

        /* Search */
        .search-bar { display:flex;align-items:center;gap:8px;margin-bottom:16px;flex-wrap:wrap; }
        .siw { position:relative;flex:1;min-width:160px; }
        .siw svg { position:absolute;left:12px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--text-muted);pointer-events:none; }
        .si-inp { width:100%;padding:9px 14px 9px 36px;font-family:'Inter',sans-serif;font-size:.875rem;background:var(--surface);border:1.5px solid var(--border2);border-radius:10px;color:var(--text);outline:none;transition:all var(--t) var(--ease); }
        .si-inp::placeholder { color:var(--text-muted); }
        .si-inp:focus { border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-bg); }
        .fsel { padding:9px 14px;font-family:'Inter',sans-serif;font-size:.875rem;background:var(--surface);border:1.5px solid var(--border2);border-radius:10px;color:var(--text);outline:none;cursor:pointer;transition:all var(--t) var(--ease); }
        .fsel option { background:var(--modal);color:var(--text); }
        .fsel:focus { border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-bg); }

        /* Empty state */
        .empty { text-align:center;padding:48px 20px;color:var(--text-muted);font-size:.875rem; }
        .empty svg { width:36px;height:36px;margin:0 auto 12px;opacity:.2;display:block; }

        /* Section headings in cards */
        .ct { font-family:'Plus Jakarta Sans',sans-serif;font-size:.95rem;font-weight:700;margin-bottom:18px;display:flex;align-items:center;gap:10px;color:var(--text); }
        .ctl { flex:1;height:1px;background:var(--border); }

        /* Page-in animations */
        .page-in { animation:pageIn .5s var(--ease) both; }
        @keyframes pageIn { from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)} }

        /* Session timeout */
        .t-banner {
            display:none;position:fixed;bottom:20px;right:20px;z-index:9999;
            background:var(--surface);border:1px solid var(--warn-border);
            border-radius:16px;padding:16px 20px;
            box-shadow:var(--shadow-lg);
            max-width:300px;
            backdrop-filter:blur(16px);
            animation:slideUp .3s var(--ease) both;
        }
        @keyframes slideUp { from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)} }
        .t-banner.show { display:block; }
        .t-title { font-family:'Plus Jakarta Sans',sans-serif;font-size:.85rem;font-weight:700;color:var(--warn);margin-bottom:4px; }
        .t-sub { font-size:.75rem;color:var(--text-soft); }
        .t-count { font-size:1.3rem;font-weight:800;color:var(--warn);margin:8px 0;font-family:'Plus Jakarta Sans',sans-serif; }
        .t-btn { padding:7px 16px;background:var(--warn-bg);border:1px solid var(--warn-border);border-radius:8px;color:var(--warn);font-family:'Plus Jakarta Sans',sans-serif;font-size:.8rem;font-weight:700;cursor:pointer; }

        /* ═══════════════════════════════════════════
           BREAKPOINTS
        ═══════════════════════════════════════════ */
        @media(min-width:900px) {
            .mobile-topbar,.mobile-content,.drawer,.drawer-overlay,.mob-tabbar { display:none!important; }
            .desktop-sidebar { display:flex; }
            .desktop-main { display:block; }
        }
        @media(max-width:899px) {
            .desktop-sidebar,.desktop-main { display:none!important; }
            .mobile-topbar { display:flex; }
            .mobile-content { display:block; }
            .mob-tabbar { display:block; }
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- DESKTOP SIDEBAR --}}
<aside class="desktop-sidebar">
    <a href="{{ route('student.dashboard') }}" class="s-logo">
        <img src="{{ asset('storage/udd-logo.jpg') }}" alt="UDD"
             onerror="this.style.display='none';this.parentElement.style.background='linear-gradient(135deg,#3b82f6,#6366f1)'">
    </a>
    <nav class="s-nav">
        @php $nav = [
            ['route'=>'student.dashboard','icon'=>'<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>','label'=>'Dashboard'],
            ['route'=>'student.map','icon'=>'<polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/>','label'=>'Campus Map'],
            ['route'=>'student.scanner','icon'=>'<rect x="3" y="3" width="5" height="5" rx="1"/><rect x="16" y="3" width="5" height="5" rx="1"/><rect x="3" y="16" width="5" height="5" rx="1"/><path d="M16 16h5v5h-5z" opacity=".4"/><line x1="16" y1="16" x2="21" y2="16"/><line x1="16" y1="21" x2="21" y2="21"/><line x1="16" y1="16" x2="16" y2="21"/>','label'=>'QR Scanner'],
            ['route'=>'student.id-card','icon'=>'<rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8" cy="12" r="2.5"/><line x1="13" y1="10" x2="19" y2="10"/><line x1="13" y1="14" x2="17" y2="14"/>','label'=>'My ID Card'],
            ['route'=>'student.settings','icon'=>'<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>','label'=>'Settings'],
        ]; @endphp
        @foreach($nav as $item)
        <a href="{{ route($item['route']) }}" class="ni {{ request()->routeIs($item['route']) ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $item['icon'] !!}</svg>
            <span class="tip">{{ $item['label'] }}</span>
        </a>
        @endforeach
    </nav>
    <div class="s-bot">
        <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
            <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        </button>
        <div class="s-av">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
        <form method="POST" action="{{ route('logout') }}" id="lf-desktop">@csrf
            <button type="submit" class="s-lo" title="Sign out">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </button>
        </form>
    </div>
</aside>

{{-- DESKTOP MAIN --}}
<div class="desktop-main">
    <div class="desktop-topbar">
        <!-- Replaced standard static title with isolated Search approach in next view -->
        <div class="topbar-right">
            <div class="gpill">
                <div class="g-av">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                Hi, {{ explode(' ',auth()->user()->name)[0] }}!
            </div>
        </div>
    </div>
    <div class="desktop-content page-in">
        <div style="margin-bottom: 24px;">
            <h1 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:1.8rem;font-weight:800;letter-spacing:-.5px;color:var(--text);">@yield('page-title')</h1>
            <p style="font-size:.9rem;color:var(--text-soft);margin-top:2px;">@yield('page-sub')</p>
        </div>
        @if(session('success'))<div class="alert alert-success"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ session('error') }}</div>@endif
        @yield('content')
    </div>
</div>

{{-- MOBILE TOPBAR --}}
<div class="mobile-topbar">
    <a href="{{ route('student.dashboard') }}" class="mob-brand">
        <div class="mob-logo"><img src="{{ asset('storage/udd-logo.jpg') }}" alt="UDD"
             onerror="this.style.display='none';this.parentElement.style.background='linear-gradient(135deg,#3b82f6,#6366f1)'"></div>
        <span class="mob-name">UDDSafeSpaces</span>
    </a>
    <div class="mob-right">
        {{-- Display-only greeting pill --}}
        <div class="mob-gpill">
            <div class="g-av">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            Hi, {{ explode(' ',auth()->user()->name)[0] }}!
        </div>
        <button class="mob-theme" onclick="toggleTheme()" title="Toggle theme">
            <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            <svg class="icon-sun"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        </button>
    </div>
</div>

{{-- MOBILE CONTENT --}}
<div class="mobile-content">
    <div class="mob-page-hdr"><h2>@yield('page-title')</h2><p>@yield('page-sub')</p></div>
    @if(session('success'))<div class="alert alert-success"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ session('error') }}</div>@endif
    @yield('content')
</div>

{{-- MOBILE BOTTOM TAB BAR --}}
<nav class="mob-tabbar" id="mobTabbar">
    <div class="mob-tabbar-inner">
        <a href="{{ route('student.dashboard') }}" class="tab-btn {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            <span>Home</span>
        </a>
        <a href="{{ route('student.map') }}" class="tab-btn {{ request()->routeIs('student.map') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
            <span>Map</span>
        </a>
        {{-- Floating QR scan button --}}
        <a href="{{ route('student.scanner') }}" class="tab-scan {{ request()->routeIs('student.scanner') ? 'active' : '' }}">
            <div class="tab-scan-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="5" height="5" rx="1"/><rect x="16" y="3" width="5" height="5" rx="1"/><rect x="3" y="16" width="5" height="5" rx="1"/><line x1="16" y1="16" x2="21" y2="16"/><line x1="16" y1="21" x2="21" y2="21"/><line x1="16" y1="16" x2="16" y2="21"/></svg>
            </div>
            <span>Scan</span>
        </a>
        <a href="{{ route('student.id-card') }}" class="tab-btn {{ request()->routeIs('student.id-card') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8" cy="12" r="2.5"/><line x1="13" y1="10" x2="19" y2="10"/><line x1="13" y1="14" x2="17" y2="14"/></svg>
            <span>ID Card</span>
        </a>
        <a href="{{ route('student.settings') }}" class="tab-btn {{ request()->routeIs('student.settings') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            <span>Settings</span>
        </a>
    </div>
</nav>

<div class="t-banner" id="tb">
    <div class="t-title">⚠ Session Expiring</div>
    <div class="t-sub">You'll be logged out in:</div>
    <div class="t-count" id="tc">60s</div>
    <button class="t-btn" onclick="rt()">Stay Logged In</button>
</div>

<script>
// Theme
const html = document.documentElement;
function applyTheme(t) {
    html.setAttribute('data-theme', t);
    localStorage.setItem('uddss_theme', t);
    const l = document.getElementById('themeLabel');
    if (l) l.textContent = t === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode';
}
function toggleTheme() { applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'); }
(function(){ applyTheme(localStorage.getItem('uddss_theme') || 'dark'); })();

// Session timeout
let rem=3600,warned=false,cd=60,ci;
function rt(){rem=3600;warned=false;clearInterval(ci);document.getElementById('tb').classList.remove('show');}
function sc(){cd=60;document.getElementById('tb').classList.add('show');ci=setInterval(()=>{cd--;document.getElementById('tc').textContent=cd+'s';if(cd<=0){clearInterval(ci);(document.getElementById('lf-desktop')||document.querySelector('form[action*="logout"]')).submit();}},1000);}
setInterval(()=>{rem--;if(rem<=60&&!warned){warned=true;sc();}if(rem<=0)(document.getElementById('lf-desktop')||document.querySelector('form[action*="logout"]')).submit();},1000);
['mousemove','keydown','click','scroll','touchstart'].forEach(e=>document.addEventListener(e,rt,{passive:true}));
</script>
@yield('modals')
@yield('scripts')
</body>
</html>