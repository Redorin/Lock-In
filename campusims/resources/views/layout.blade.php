<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampuSIMS — @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:       #0d0f14;
            --ink-soft:  #3a3e4a;
            --ink-muted: #7a7f8e;
            --surface:   #f5f4f0;
            --card:      #ffffff;
            --accent:    #c8f04d;
            --accent-dk: #a8d030;
            --danger:    #e05252;
            --border:    #e2e1db;
            --radius:    14px;
            --shadow:    0 4px 32px rgba(13,15,20,.08), 0 1px 4px rgba(13,15,20,.04);
        }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        /* ── decorative background ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 10% 20%, rgba(200,240,77,.18) 0%, transparent 70%),
                radial-gradient(ellipse 50% 60% at 85% 80%, rgba(200,240,77,.10) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        /* ── brand bar ── */
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
            text-decoration: none;
        }
        .brand-icon {
            width: 38px; height: 38px;
            background: var(--ink);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-icon svg { width: 20px; height: 20px; }
        .brand-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -.5px;
            color: var(--ink);
        }
        .brand-name span { color: var(--accent-dk); }

        /* ── card ── */
        .auth-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius) * 1.5);
            box-shadow: var(--shadow);
            padding: 44px 48px;
            width: 100%;
            max-width: 460px;
            animation: cardIn .5s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── form elements (shared) ── */
        .auth-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: -.5px;
            line-height: 1.1;
            margin-bottom: 6px;
        }
        .auth-sub {
            font-size: .9rem;
            color: var(--ink-muted);
            margin-bottom: 32px;
        }

        .field { margin-bottom: 18px; }
        .field label {
            display: block;
            font-size: .78rem;
            font-weight: 500;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--ink-soft);
            margin-bottom: 6px;
        }
        .field input {
            width: 100%;
            padding: 12px 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: .95rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            color: var(--ink);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .field input:focus {
            border-color: var(--ink);
            box-shadow: 0 0 0 3px rgba(13,15,20,.07);
        }
        .field input.is-invalid { border-color: var(--danger); }
        .invalid-feedback {
            font-size: .8rem;
            color: var(--danger);
            margin-top: 5px;
        }

        .btn-primary {
            width: 100%;
            padding: 13px;
            background: var(--ink);
            color: var(--accent);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .02em;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            margin-top: 8px;
            transition: background .2s, transform .15s;
        }
        .btn-primary:hover  { background: #1c2030; transform: translateY(-1px); }
        .btn-primary:active { transform: translateY(0); }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: .875rem;
            color: var(--ink-muted);
        }
        .auth-footer a {
            color: var(--ink);
            font-weight: 500;
            text-decoration: none;
            border-bottom: 1.5px solid var(--accent-dk);
            transition: color .2s;
        }
        .auth-footer a:hover { color: var(--accent-dk); }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: var(--border);
            font-size: .78rem;
            color: var(--ink-muted);
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* alerts */
        .alert {
            padding: 12px 14px;
            border-radius: var(--radius);
            font-size: .875rem;
            margin-bottom: 20px;
        }
        .alert-danger  { background: #fdf0f0; color: var(--danger); border: 1px solid #f5c6c6; }
        .alert-success { background: #f4fde8; color: #4a7c10; border: 1px solid #c8f04d; }

        /* two-col grid for registration */
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        @media (max-width: 520px) {
            .auth-card { padding: 32px 24px; }
            .field-row { grid-template-columns: 1fr; }
        }
    </style>
    @yield('styles')
</head>
<body>
<div class="page-wrap">

    <a href="/" class="brand">
        <div class="brand-icon">
            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2L3 6v8l7 4 7-4V6L10 2z" stroke="#c8f04d" stroke-width="1.6" stroke-linejoin="round"/>
                <path d="M10 2v12M3 6l7 4 7-4" stroke="#c8f04d" stroke-width="1.6" stroke-linejoin="round"/>
            </svg>
        </div>
        <span class="brand-name">Campu<span>SIMS</span></span>
    </a>

    @yield('content')

</div>
</body>
</html>