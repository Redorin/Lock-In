<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>UDDSafeSpaces — Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --ease:cubic-bezier(.22,1,.36,1);
            --ease-back:cubic-bezier(.34,1.56,.64,1);
            --t:.22s;
        }
        html,body{min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;background:#f0f4f8;color:#0f172a;-webkit-font-smoothing:antialiased;}

        /* ── Page ── */
        .page{min-height:100vh;display:flex;}

        /* ── Left panel ── */
        .lpanel{
            display:none;width:420px;flex-shrink:0;
            background:linear-gradient(160deg,#0c1f5c 0%,#1e3a8a 45%,#2563eb 100%);
            flex-direction:column;align-items:center;justify-content:center;
            padding:56px 48px;position:relative;overflow:hidden;
        }
        /* Decorative circles */
        .lpanel::before{content:'';position:absolute;top:-80px;right:-80px;width:320px;height:320px;border-radius:50%;border:1px solid rgba(255,255,255,.06);pointer-events:none;}
        .lpanel::after{content:'';position:absolute;bottom:-60px;left:-60px;width:240px;height:240px;border-radius:50%;background:rgba(255,255,255,.03);pointer-events:none;}
        .lp-inner{position:relative;z-index:1;display:flex;flex-direction:column;align-items:center;text-align:center;}

        .lp-seal-wrap{
            width:160px;height:160px;border-radius:50%;
            background:#fff;
            padding:8px;
            box-shadow:0 0 0 12px rgba(255,255,255,.08),0 20px 60px rgba(0,0,0,.4);
            margin-bottom:32px;
            animation:sealIn .8s var(--ease) both;
        }
        @keyframes sealIn{from{opacity:0;transform:scale(.8)}to{opacity:1;transform:scale(1)}}
        .lp-seal-wrap img{width:100%;height:100%;border-radius:50%;object-fit:cover;display:block;}

        .lp-app{font-family:'Plus Jakarta Sans',sans-serif;font-size:2rem;font-weight:800;color:#fff;letter-spacing:-.6px;line-height:1.1;margin-bottom:8px;animation:fadeUp .7s .15s var(--ease) both;}
        .lp-uni{font-size:.82rem;color:rgba(255,255,255,.5);letter-spacing:.05em;margin-bottom:24px;animation:fadeUp .7s .2s var(--ease) both;}
        @keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}

        .lp-divider{width:40px;height:2px;background:rgba(255,255,255,.15);border-radius:99px;margin:0 auto 28px;animation:fadeUp .7s .25s var(--ease) both;}

        .lp-features{display:flex;flex-direction:column;gap:16px;width:100%;animation:fadeUp .7s .3s var(--ease) both;}
        .lp-feat{display:flex;align-items:center;gap:14px;text-align:left;}
        .lp-feat-icon{width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .lp-feat-icon svg{width:17px;height:17px;color:rgba(255,255,255,.8);}
        .lp-feat-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:.85rem;font-weight:600;color:#fff;margin-bottom:2px;}
        .lp-feat-sub{font-size:.76rem;color:rgba(255,255,255,.45);line-height:1.4;}

        /* ── Right panel ── */
        .rpanel{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px;background:#f0f4f8;position:relative;overflow:hidden;}
        .rpanel::before{content:'';position:absolute;top:-100px;right:-100px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(37,99,235,.05) 0%,transparent 70%);pointer-events:none;}
        .form-wrap{width:100%;max-width:400px;position:relative;z-index:1;}

        /* Mobile brand */
        .mob-brand{display:flex;align-items:center;gap:12px;margin-bottom:28px;justify-content:center;}
        .mob-seal{width:48px;height:48px;border-radius:50%;overflow:hidden;border:2px solid rgba(37,99,235,.2);background:#fff;box-shadow:0 2px 12px rgba(37,99,235,.12);}
        .mob-seal img{width:100%;height:100%;object-fit:cover;display:block;}
        .mob-app{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.1rem;font-weight:800;color:#1e3a8a;letter-spacing:-.3px;}
        .mob-uni{font-size:.7rem;color:#94a3b8;margin-top:2px;}

        /* ── Form card ── */
        .card{
            background:#fff;border:1px solid rgba(0,0,0,.08);border-radius:32px;
            padding:40px 36px;
            box-shadow:0 8px 48px rgba(0,0,0,.06),0 1px 4px rgba(0,0,0,.04),inset 0 1px 0 rgba(255,255,255,.8);
            animation:cardIn .6s var(--ease) both;
        }
        @keyframes cardIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}

        .card-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;margin-bottom:8px;}
        .card-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.75rem;font-weight:800;letter-spacing:-.6px;color:#0f172a;margin-bottom:4px;}
        .card-sub{font-size:.85rem;color:#94a3b8;margin-bottom:28px;}

        /* Alerts */
        .alert{padding:12px 14px;border-radius:12px;font-size:.83rem;margin-bottom:18px;display:flex;align-items:center;gap:10px;}
        .alert svg{width:15px;height:15px;flex-shrink:0;}
        .alert-danger{background:rgba(220,38,38,.06);color:#dc2626;border:1px solid rgba(220,38,38,.15);}
        .alert-success{background:rgba(37,99,235,.06);color:#1e3a8a;border:1px solid rgba(37,99,235,.15);}

        /* Fields */
        .field{margin-bottom:16px;}
        .fh{display:flex;align-items:center;justify-content:space-between;margin-bottom:7px;}
        .field label,.fh label{display:block;font-size:.71rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;}
        .fl{font-size:.75rem;color:#94a3b8;text-decoration:none;transition:color var(--t) var(--ease);}
        .fl:hover{color:#2563eb;}
        .input-wrap{position:relative;}
        .field input{
            width:100%;padding:14px 16px;
            font-family:'Plus Jakarta Sans',sans-serif;font-size:.95rem;font-weight:600;
            background:#f8fafc;border:1.5px solid rgba(0,0,0,.08);
            border-radius:14px;color:#0f172a;outline:none;
            transition:all var(--t) var(--ease);
        }
        .field input::placeholder{color:#cbd5e1;}
        .field input:focus{
            border-color:#2563eb;background:#fff;
            box-shadow:0 0 0 3px rgba(37,99,235,.1);
        }
        .field input.is-invalid{border-color:#dc2626;}
        .invalid-feedback{font-size:.77rem;color:#dc2626;margin-top:5px;}

        /* Remember me */
        .rem{display:flex;align-items:center;gap:9px;margin:2px 0 20px;}
        .custom-cb{width:17px;height:17px;border-radius:5px;border:1.5px solid rgba(0,0,0,.15);background:#f8fafc;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all var(--t) var(--ease);}
        .custom-cb.checked{background:#2563eb;border-color:#2563eb;}
        .custom-cb svg{width:10px;height:10px;color:#fff;opacity:0;transition:opacity .15s;}
        .custom-cb.checked svg{opacity:1;}
        .rem label{font-size:.84rem;color:#475569;cursor:pointer;user-select:none;}
        input[type=checkbox].hidden-cb{display:none;}

        /* Submit */
        .sbtn{
            width:100%;padding:14px;
            background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 60%,#3b82f6 100%);
            color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:1.05rem;
            border:none;border-radius:99px;cursor:pointer;
            box-shadow:0 4px 20px rgba(37,99,235,.25),inset 0 1px 0 rgba(255,255,255,.2);
            transition:all var(--t) var(--ease);
            letter-spacing:.01em;
            position:relative;overflow:hidden;
        }
        .sbtn::before{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.1) 0%,transparent 100%);pointer-events:none;}
        .sbtn:hover{transform:translateY(-1px);box-shadow:0 6px 28px rgba(37,99,235,.35);}
        .sbtn:active{transform:translateY(0);box-shadow:0 2px 12px rgba(37,99,235,.25);}

        /* Footer */
        .auth-footer{text-align:center;margin-top:20px;font-size:.85rem;color:#94a3b8;}
        .auth-footer a{color:#2563eb;font-weight:600;text-decoration:none;transition:color var(--t) var(--ease);}
        .auth-footer a:hover{color:#1d4ed8;}

        @media(min-width:900px){.lpanel{display:flex;}.mob-brand{display:none;}}
        @media(max-width:460px){.card{padding:28px 20px;}.card-title{font-size:1.5rem;}}
    </style>
</head>
<body>
<div class="page">

    {{-- Left panel --}}
    <div class="lpanel">
        <div class="lp-inner">
            <div class="lp-seal-wrap">
                <img src="{{ asset('storage/udd-logo.jpg') }}" alt="Universidad de Dagupan"
                     onerror="this.style.display='none'">
            </div>
            <div class="lp-app">UDDSafeSpaces</div>
            <div class="lp-uni">Universidad de Dagupan</div>
            <div class="lp-divider"></div>
            <div class="lp-features">
                <div class="lp-feat">
                    <div class="lp-feat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></div>
                    <div><div class="lp-feat-title">Live Space Monitoring</div><div class="lp-feat-sub">Real-time occupancy tracking across all campus buildings.</div></div>
                </div>
                <div class="lp-feat">
                    <div class="lp-feat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="5" height="5" rx="1"/><rect x="16" y="3" width="5" height="5" rx="1"/><rect x="3" y="16" width="5" height="5" rx="1"/><line x1="16" y1="16" x2="21" y2="21"/></svg></div>
                    <div><div class="lp-feat-title">QR Check-In System</div><div class="lp-feat-sub">Daily-rotating QR codes for secure and fast check-ins.</div></div>
                </div>
                <div class="lp-feat">
                    <div class="lp-feat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                    <div><div class="lp-feat-title">Secure &amp; Verified</div><div class="lp-feat-sub">Admin-reviewed accounts keep the system safe and reliable.</div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right form --}}
    <div class="rpanel">
        <div class="form-wrap">
            <div class="mob-brand">
                <div class="mob-seal"><img src="{{ asset('storage/udd-logo.jpg') }}" alt="UDD"></div>
                <div><div class="mob-app">UDDSafeSpaces</div><div class="mob-uni">Universidad de Dagupan</div></div>
            </div>

            <div class="card">
                <div class="card-eyebrow">Student &amp; Admin Portal</div>
                <h1 class="card-title">Welcome back.</h1>
                <p class="card-sub">Sign in to continue to UDDSafeSpaces.</p>

                @if(session('success'))<div class="alert alert-success"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>@endif
                @if(session('error'))<div class="alert alert-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ session('error') }}</div>@endif
                @if($errors->any())<div class="alert alert-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $errors->first() }}</div>@endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="field">
                        <label>Email address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="you@udd.edu.ph"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <div class="fh">
                            <label>Password</label>
                            <a href="{{ route('password.request') }}" class="fl">Forgot password?</a>
                        </div>
                        <input type="password" name="password" placeholder="••••••••"
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="rem">
                        <div class="custom-cb" id="cbWrap" onclick="toggleCb()">
                            <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="2 6 5 9 10 3"/></svg>
                        </div>
                        <input type="checkbox" name="remember" id="remember" class="hidden-cb">
                        <label for="remember" onclick="toggleCb()">Keep me signed in</label>
                    </div>

                    <button type="submit" class="sbtn">Sign In →</button>
                </form>
            </div>

            <p class="auth-footer">
                Don't have an account? <a href="{{ route('register') }}">Create one</a>
            </p>
        </div>
    </div>
</div>

<script>
function toggleCb(){
    const cb=document.getElementById('remember');
    const wrap=document.getElementById('cbWrap');
    cb.checked=!cb.checked;
    wrap.classList.toggle('checked',cb.checked);
}
</script>
</body>
</html>