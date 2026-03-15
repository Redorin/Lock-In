<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampuSIMS — Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:           #091510;
            --glass:        rgba(255,255,255,.04);
            --glass-border: rgba(255,255,255,.08);
            --accent:       #00e5a0;
            --text:         #ddeee6;
            --text-soft:    rgba(221,238,230,.55);
            --text-muted:   rgba(221,238,230,.28);
            --radius-lg:    22px;
            --radius-md:    14px;
            --radius-sm:    10px;
            --danger:       #ff7070;
        }

        html, body {
            min-height: 100vh;
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 70% 60% at 10% 10%,  rgba(0,229,160,.14) 0%, transparent 55%),
                radial-gradient(ellipse 50% 50% at 85% 85%,  rgba(124,111,247,.12) 0%, transparent 55%),
                radial-gradient(ellipse 40% 35% at 60% 20%,  rgba(200,240,77,.06)  0%, transparent 50%);
        }

        body::after {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                radial-gradient(circle 300px at 80% 20%, rgba(0,229,160,.06) 0%, transparent 70%),
                radial-gradient(circle 200px at 20% 75%, rgba(124,111,247,.07) 0%, transparent 70%);
        }

        .page {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 40px 20px;
        }

        .auth-wrap { width: 100%; max-width: 420px; }

        .brand {
            display: flex; align-items: center; gap: 12px;
            justify-content: center; margin-bottom: 36px;
            text-decoration: none;
        }
        .brand-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--accent), #00b87a);
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 24px rgba(0,229,160,.3);
        }
        .brand-icon svg { width: 22px; height: 22px; }
        .brand-name { font-size: 1.2rem; font-weight: 800; letter-spacing: -.4px; }
        .brand-name span { color: var(--accent); }

        .auth-card {
            background: rgba(255,255,255,.04);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-lg);
            backdrop-filter: blur(24px);
            padding: 40px 36px;
            animation: cardIn .5s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-title { font-size: 1.7rem; font-weight: 800; letter-spacing: -.5px; margin-bottom: 6px; }
        .auth-sub   { font-size: .85rem; color: var(--text-soft); margin-bottom: 28px; }

        .alert { padding: 11px 14px; border-radius: var(--radius-sm); font-size: .83rem; margin-bottom: 20px; }
        .alert-danger  { background: rgba(255,112,112,.08); color: var(--danger); border: 1px solid rgba(255,112,112,.18); }
        .alert-success { background: rgba(0,229,160,.08); color: var(--accent); border: 1px solid rgba(0,229,160,.18); }

        .field { margin-bottom: 16px; }
        .field-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 7px; }
        .field label {
            display: block;
            font-size: .71rem; font-weight: 600;
            letter-spacing: .08em; text-transform: uppercase;
            color: var(--text-muted);
        }
        .field-link {
            font-size: .75rem; color: var(--text-muted); text-decoration: none;
            border-bottom: 1px dashed rgba(221,238,230,.2); transition: color .15s;
        }
        .field-link:hover { color: var(--accent); }

        .field input {
            width: 100%; padding: 12px 16px;
            font-family: 'Outfit', sans-serif; font-size: .9rem;
            background: rgba(255,255,255,.04);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-md);
            color: var(--text); outline: none;
            transition: border-color .2s, background .2s;
        }
        .field input::placeholder { color: var(--text-muted); }
        .field input:focus { border-color: rgba(0,229,160,.4); background: rgba(0,229,160,.03); }
        .field input.is-invalid { border-color: rgba(255,112,112,.4); }
        .invalid-feedback { font-size: .77rem; color: var(--danger); margin-top: 5px; }

        .remember-row {
            display: flex; align-items: center; gap: 8px;
            margin: 4px 0 20px;
        }
        .remember-row input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--accent); cursor: pointer; }
        .remember-row label { font-size: .83rem; color: var(--text-soft); cursor: pointer; }

        .btn-submit {
            width: 100%; padding: 13px;
            background: var(--accent); color: #091510;
            font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem;
            border: none; border-radius: var(--radius-md); cursor: pointer;
            box-shadow: 0 4px 24px rgba(0,229,160,.25);
            transition: opacity .18s, transform .15s;
        }
        .btn-submit:hover  { opacity: .88; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        .auth-footer {
            text-align: center; margin-top: 22px;
            font-size: .85rem; color: var(--text-muted);
        }
        .auth-footer a {
            color: var(--accent); font-weight: 600; text-decoration: none;
            border-bottom: 1px solid rgba(0,229,160,.3);
        }
        .auth-footer a:hover { border-color: var(--accent); }
    </style>
</head>
<body>
<div class="page">
    <div class="auth-wrap">

        <a href="/" class="brand">
            <div class="brand-icon">
                <svg viewBox="0 0 20 20" fill="none">
                    <path d="M10 2L3 6v8l7 4 7-4V6L10 2z" fill="#091510"/>
                    <path d="M10 2v12M3 6l7 4 7-4" stroke="#091510" stroke-width="1.5"/>
                </svg>
            </div>
            <span class="brand-name">Campu<span>SIMS</span></span>
        </a>

        <div class="auth-card">
            <h1 class="auth-title">Welcome back.</h1>
            <p class="auth-sub">Sign in to your CampuSIMS account.</p>

            {{-- Success message from registration --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}" placeholder="you@school.edu"
                           autocomplete="email"
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <div class="field-header">
                        <label for="password">Password</label>
                        <a href="{{ route('password.request') }}" class="field-link">Forgot?</a>
                    </div>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••" autocomplete="current-password"
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Keep me signed in</label>
                </div>

                <button type="submit" class="btn-submit">Sign In →</button>
            </form>
        </div>

        <p class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </p>

    </div>
</div>
</body>
</html>