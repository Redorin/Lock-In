<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampuSIMS — Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{--bg:#091510;--glass-border:rgba(255,255,255,.08);--accent:#00e5a0;--text:#ddeee6;--text-soft:rgba(221,238,230,.55);--text-muted:rgba(221,238,230,.28);--radius-lg:22px;--radius-md:14px;--danger:#ff7070}
        html,body{min-height:100vh;font-family:'Outfit',sans-serif;background:var(--bg);color:var(--text);-webkit-font-smoothing:antialiased}
        body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;background:radial-gradient(ellipse 70% 60% at 10% 10%,rgba(0,229,160,.14) 0%,transparent 55%),radial-gradient(ellipse 50% 50% at 85% 85%,rgba(124,111,247,.12) 0%,transparent 55%)}
        .page{position:relative;z-index:1;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 20px}
        .auth-wrap{width:100%;max-width:420px}
        .brand{display:flex;align-items:center;gap:12px;justify-content:center;margin-bottom:36px;text-decoration:none}
        .brand-icon{width:44px;height:44px;background:linear-gradient(135deg,var(--accent),#00b87a);border-radius:13px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 24px rgba(0,229,160,.3)}
        .brand-icon svg{width:22px;height:22px}
        .brand-name{font-size:1.2rem;font-weight:800;letter-spacing:-.4px}
        .brand-name span{color:var(--accent)}
        .auth-card{background:rgba(255,255,255,.04);border:1px solid var(--glass-border);border-radius:var(--radius-lg);backdrop-filter:blur(24px);padding:40px 36px;animation:cardIn .5s cubic-bezier(.22,1,.36,1) both}
        @keyframes cardIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        .auth-title{font-size:1.5rem;font-weight:800;letter-spacing:-.5px;margin-bottom:6px}
        .auth-sub{font-size:.85rem;color:var(--text-soft);margin-bottom:28px}
        .alert{padding:11px 14px;border-radius:10px;font-size:.83rem;margin-bottom:20px}
        .alert-danger{background:rgba(255,112,112,.08);color:var(--danger);border:1px solid rgba(255,112,112,.18)}
        .field{margin-bottom:16px}
        .field label{display:block;font-size:.71rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:7px}
        .field input{width:100%;padding:12px 16px;font-family:'Outfit',sans-serif;font-size:.9rem;background:rgba(255,255,255,.04);border:1px solid var(--glass-border);border-radius:var(--radius-md);color:var(--text);outline:none;transition:border-color .2s}
        .field input::placeholder{color:var(--text-muted)}
        .field input:focus{border-color:rgba(0,229,160,.4);background:rgba(0,229,160,.03)}
        .btn-submit{width:100%;padding:13px;background:var(--accent);color:#091510;font-family:'Outfit',sans-serif;font-weight:700;font-size:1rem;border:none;border-radius:var(--radius-md);cursor:pointer;box-shadow:0 4px 24px rgba(0,229,160,.25);transition:opacity .18s,transform .15s;margin-top:8px}
        .btn-submit:hover{opacity:.88;transform:translateY(-1px)}
        .auth-footer{text-align:center;margin-top:22px;font-size:.85rem;color:var(--text-muted)}
        .auth-footer a{color:var(--accent);font-weight:600;text-decoration:none;border-bottom:1px solid rgba(0,229,160,.3)}
    </style>
</head>
<body>
<div class="page">
    <div class="auth-wrap">
        <a href="/" class="brand">
            <div class="brand-icon"><svg viewBox="0 0 20 20" fill="none"><path d="M10 2L3 6v8l7 4 7-4V6L10 2z" fill="#091510"/><path d="M10 2v12M3 6l7 4 7-4" stroke="#091510" stroke-width="1.5"/></svg></div>
            <span class="brand-name">Campu<span>SIMS</span></span>
        </a>
        <div class="auth-card">
            <h1 class="auth-title">Set new password.</h1>
            <p class="auth-sub">Enter your new password below.</p>
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                <div class="field">
                    <label>New Password</label>
                    <input type="password" name="password" placeholder="Min. 8 characters" required>
                </div>
                <div class="field">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Repeat password" required>
                </div>
                <button type="submit" class="btn-submit">Reset Password →</button>
            </form>
        </div>
        <p class="auth-footer"><a href="{{ route('login') }}">← Back to Sign In</a></p>
    </div>
</div>
</body>
</html>