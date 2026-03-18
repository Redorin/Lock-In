<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>UDDSafeSpaces — Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html,body{min-height:100vh;font-family:'Inter',sans-serif;background:#f0f4f8;color:#0f172a;-webkit-font-smoothing:antialiased;}
        .page{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 20px;background:#f0f4f8;position:relative;overflow:hidden;}
        .page::before{content:'';position:absolute;top:-100px;right:-100px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(37,99,235,.06) 0%,transparent 70%);pointer-events:none;}
        .page::after{content:'';position:absolute;bottom:-80px;left:-80px;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.04) 0%,transparent 70%);pointer-events:none;}
        .wrap{width:100%;max-width:420px;position:relative;z-index:1;}

        /* Brand */
        .brand{display:flex;align-items:center;gap:12px;justify-content:center;margin-bottom:28px;}
        .brand-seal{width:48px;height:48px;border-radius:50%;overflow:hidden;border:2px solid rgba(37,99,235,.2);background:#fff;box-shadow:0 4px 16px rgba(37,99,235,.12);}
        .brand-seal img{width:100%;height:100%;object-fit:cover;display:block;}
        .brand-name{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.1rem;font-weight:800;color:#1e3a8a;letter-spacing:-.3px;}
        .brand-uni{font-size:.7rem;color:#94a3b8;margin-top:2px;}

        /* Card */
        .card{background:#fff;border:1px solid rgba(0,0,0,.08);border-radius:20px;padding:36px 32px;box-shadow:0 4px 24px rgba(0,0,0,.06),0 1px 4px rgba(0,0,0,.04),inset 0 1px 0 rgba(255,255,255,.8);animation:cardIn .5s cubic-bezier(.22,1,.36,1) both;}
        @keyframes cardIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
        .card-icon{width:52px;height:52px;border-radius:14px;background:rgba(37,99,235,.07);border:1px solid rgba(37,99,235,.15);display:flex;align-items:center;justify-content:center;margin-bottom:20px;}
        .card-icon svg{width:24px;height:24px;color:#2563eb;}
        .card-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.6rem;font-weight:800;letter-spacing:-.5px;color:#0f172a;margin-bottom:6px;}
        .card-sub{font-size:.85rem;color:#94a3b8;margin-bottom:24px;line-height:1.6;}

        /* Alert */
        .alert{padding:12px 14px;border-radius:12px;font-size:.83rem;margin-bottom:16px;display:flex;align-items:flex-start;gap:10px;}
        .alert svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}
        .alert-danger{background:rgba(220,38,38,.06);color:#dc2626;border:1px solid rgba(220,38,38,.15);}

        /* Link box */
        .link-box{background:rgba(37,99,235,.05);border:1.5px solid rgba(37,99,235,.15);border-radius:14px;padding:18px;margin-bottom:18px;}
        .lb-top{display:flex;align-items:center;gap:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.82rem;font-weight:700;color:#1e3a8a;margin-bottom:10px;}
        .lb-top svg{width:15px;height:15px;}
        .lb-url{display:block;word-break:break-all;font-size:.76rem;color:#2563eb;background:#fff;border:1px solid rgba(37,99,235,.15);border-radius:8px;padding:8px 12px;margin-bottom:10px;line-height:1.5;}
        .copy-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:#2563eb;border:none;color:#fff;border-radius:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.75rem;font-weight:700;cursor:pointer;transition:all .2s;}
        .copy-btn:hover{background:#1d4ed8;transform:translateY(-1px);}
        .copy-btn svg{width:13px;height:13px;}

        /* Fields */
        .field{margin-bottom:16px;}
        .field label{display:block;font-size:.71rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin-bottom:7px;}
        .field input{width:100%;padding:12px 14px;font-family:'Inter',sans-serif;font-size:.9rem;background:#f8fafc;border:1.5px solid rgba(0,0,0,.1);border-radius:10px;color:#0f172a;outline:none;transition:all .2s;}
        .field input::placeholder{color:#cbd5e1;}
        .field input:focus{border-color:#2563eb;background:#fff;box-shadow:0 0 0 3px rgba(37,99,235,.1);}

        /* Submit */
        .sbtn{width:100%;padding:13px;background:linear-gradient(135deg,#1e3a8a,#2563eb,#3b82f6);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.95rem;border:none;border-radius:11px;cursor:pointer;box-shadow:0 4px 20px rgba(37,99,235,.3);transition:all .2s;position:relative;overflow:hidden;}
        .sbtn::before{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.1) 0%,transparent 100%);pointer-events:none;}
        .sbtn:hover{transform:translateY(-1px);box-shadow:0 6px 28px rgba(37,99,235,.35);}
        .sbtn:active{transform:translateY(0);}

        /* Footer */
        .auth-footer{text-align:center;margin-top:20px;font-size:.85rem;color:#94a3b8;}
        .auth-footer a{color:#2563eb;font-weight:600;text-decoration:none;}
        .auth-footer a:hover{color:#1d4ed8;}

        @media(max-width:460px){.card{padding:26px 18px;}.card-title{font-size:1.4rem;}}
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
            <div class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
            <h1 class="card-title">Reset your password.</h1>
            <p class="card-sub">Enter your registered email and we'll generate a reset link for you.</p>

            @if(session('reset_link'))
            <div class="link-box">
                <div class="lb-top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>Reset link ready — copy and open in browser:</div>
                <a href="{{ session('reset_link') }}" id="rl" class="lb-url">{{ session('reset_link') }}</a>
                <button class="copy-btn" onclick="copyLink()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    Copy Link
                </button>
            </div>
            @endif
            @if($errors->any())<div class="alert alert-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $errors->first() }}</div>@endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="field"><label>Email Address</label><input type="email" name="email" value="{{ old('email') }}" placeholder="you@udd.edu.ph" required></div>
                <button type="submit" class="sbtn">Generate Reset Link →</button>
            </form>
        </div>
        <p class="auth-footer"><a href="{{ route('login') }}">← Back to Sign In</a></p>
    </div>
</div>
<script>
function copyLink(){
    navigator.clipboard.writeText(document.getElementById('rl').href).then(()=>{
        const b=document.querySelector('.copy-btn');
        b.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Copied!';
        setTimeout(()=>{b.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Copy Link';},2000);
    });
}
</script>
</body>
</html>