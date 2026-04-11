<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>UDDSafeSpaces — Set New Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html,body{min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;background:#f0f4f8;color:#0f172a;-webkit-font-smoothing:antialiased;}
        .page{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 20px;background:#f0f4f8;position:relative;overflow:hidden;}
        .page::before{content:'';position:absolute;top:-100px;right:-100px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(37,99,235,.06) 0%,transparent 70%);pointer-events:none;}
        .wrap{width:100%;max-width:420px;position:relative;z-index:1;}
        .brand{display:flex;align-items:center;gap:12px;justify-content:center;margin-bottom:28px;}
        .brand-seal{width:48px;height:48px;border-radius:50%;overflow:hidden;border:2px solid rgba(37,99,235,.2);background:#fff;box-shadow:0 4px 16px rgba(37,99,235,.12);}
        .brand-seal img{width:100%;height:100%;object-fit:cover;display:block;}
        .brand-name{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.1rem;font-weight:800;color:#1e3a8a;letter-spacing:-.3px;}
        .brand-uni{font-size:.7rem;color:#94a3b8;margin-top:2px;}
        .card{background:#fff;border:1px solid rgba(0,0,0,.08);border-radius:32px;padding:40px 36px;box-shadow:0 8px 48px rgba(0,0,0,.06),inset 0 1px 0 rgba(255,255,255,.8);animation:cardIn .5s cubic-bezier(.22,1,.36,1) both;}
        @keyframes cardIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
        .card-icon{width:52px;height:52px;border-radius:14px;background:rgba(37,99,235,.07);border:1px solid rgba(37,99,235,.15);display:flex;align-items:center;justify-content:center;margin-bottom:20px;}
        .card-icon svg{width:24px;height:24px;color:#2563eb;}
        .card-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.6rem;font-weight:800;letter-spacing:-.5px;color:#0f172a;margin-bottom:6px;}
        .card-sub{font-size:.85rem;color:#94a3b8;margin-bottom:24px;}
        .alert{padding:12px 14px;border-radius:12px;font-size:.83rem;margin-bottom:16px;display:flex;align-items:center;gap:10px;}
        .alert svg{width:15px;height:15px;flex-shrink:0;}
        .alert-danger{background:rgba(220,38,38,.06);color:#dc2626;border:1px solid rgba(220,38,38,.15);}
        .field{margin-bottom:16px;}
        .field label{display:block;font-size:.71rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin-bottom:7px;}
        .field input{width:100%;padding:14px 16px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.95rem;font-weight:600;background:#f8fafc;border:1.5px solid rgba(0,0,0,.08);border-radius:14px;color:#0f172a;outline:none;transition:all .2s;}
        .field input::placeholder{color:#cbd5e1;}
        .field input:focus{border-color:#2563eb;background:#ffffff;box-shadow:0 0 0 4px rgba(37,99,235,.15);}
        .sbtn{width:100%;padding:14px;background:linear-gradient(135deg,#1e3a8a,#2563eb,#3b82f6);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:1.05rem;border:none;border-radius:99px;cursor:pointer;box-shadow:0 4px 20px rgba(37,99,235,.3);transition:all .2s;margin-top:4px;position:relative;overflow:hidden;}
        .sbtn::before{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.1) 0%,transparent 100%);pointer-events:none;}
        .sbtn:hover{transform:translateY(-2px);box-shadow:0 6px 28px rgba(37,99,235,.35);}
        .sbtn:active{transform:translateY(0) scale(.98);}
        .auth-footer{text-align:center;margin-top:20px;font-size:.85rem;color:#94a3b8;}
        .auth-footer a{color:#2563eb;font-weight:600;text-decoration:none;}
        @media(max-width:460px){.card{padding:26px 18px;}}
    </style>
</head>
<body>
<div class="page">
    <div class="wrap">
        <div class="brand">
            <div class="brand-seal"><img src="{{ asset('storage/udd-logo.jpg') }}" alt="UDD" onerror="this.style.display='none'"></div>
            <div><div class="brand-name">UDDSafeSpaces</div><div class="brand-uni">Universidad de Dagupan</div></div>
        </div>
        <div class="card">
            <div class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
            <h1 class="card-title">Set new password.</h1>
            <p class="card-sub">Enter and confirm your new password to complete the reset.</p>
            @if($errors->any())<div class="alert alert-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                <div class="field"><label>New Password</label><input type="password" name="password" placeholder="Min. 8 characters" required></div>
                <div class="field"><label>Confirm Password</label><input type="password" name="password_confirmation" placeholder="Repeat new password" required></div>
                <button type="submit" class="sbtn">Reset Password →</button>
            </form>
        </div>
        <p class="auth-footer"><a href="{{ route('login') }}">← Back to Sign In</a></p>
    </div>
</div>
</body>
</html>