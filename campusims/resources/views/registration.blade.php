<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>UDDSafeSpaces — Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --bg:#eef2f7;
            --surface:#ffffff;
            --surface2:#f4f7fb;
            --border:rgba(0,0,0,.08);
            --border2:rgba(0,0,0,.12);
            --accent:#1e3a8a;
            --accent-mid:#2563eb;
            --accent-light:#eff6ff;
            --accent-border:rgba(37,99,235,.18);
            --text:#0f172a;
            --text-soft:#475569;
            --text-muted:#94a3b8;
            --danger:#dc2626;
            --danger-bg:rgba(220,38,38,.07);
            --danger-border:rgba(220,38,38,.2);
        }
        html,body{height:100%;font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);-webkit-font-smoothing:antialiased;}

        /* ── Page shell ── */
        .page{min-height:100vh;display:flex;}

        /* ── Left panel ── */
        .lpanel{
            display:none;
            width:380px;flex-shrink:0;
            background:linear-gradient(170deg,#0c1f5c 0%,#1e3a8a 40%,#1d4ed8 100%);
            flex-direction:column;
            align-items:center;justify-content:center;
            padding:56px 40px;
            position:sticky;top:0;height:100vh;
            overflow:hidden;
        }
        .lpanel::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 90% 70% at 20% 20%,rgba(255,255,255,.07) 0%,transparent 60%);}
        .lpanel::after{content:'';position:absolute;bottom:-80px;right:-80px;width:260px;height:260px;border-radius:50%;border:1px solid rgba(255,255,255,.06);}

        .lp-seal{
            width:152px;height:152px;border-radius:50%;
            border:4px solid rgba(255,255,255,.22);
            box-shadow:0 0 0 10px rgba(255,255,255,.05),0 12px 48px rgba(0,0,0,.35);
            overflow:hidden;background:#fff;
            margin-bottom:30px;position:relative;z-index:1;
        }
        .lp-seal img{width:100%;height:100%;object-fit:cover;display:block;}

        .lp-app{font-size:1.8rem;font-weight:800;color:#fff;text-align:center;letter-spacing:-.6px;line-height:1.1;margin-bottom:8px;position:relative;z-index:1;}
        .lp-uni{font-size:.78rem;color:rgba(255,255,255,.45);text-align:center;letter-spacing:.05em;margin-bottom:20px;position:relative;z-index:1;}

        .lp-divider{width:36px;height:2px;background:rgba(255,255,255,.18);border-radius:99px;margin:0 auto 20px;position:relative;z-index:1;}

        .lp-steps{display:flex;flex-direction:column;gap:14px;width:100%;position:relative;z-index:1;}
        .lp-step{display:flex;align-items:flex-start;gap:12px;}
        .lp-step-num{
            width:26px;height:26px;border-radius:50%;
            background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);
            display:flex;align-items:center;justify-content:center;
            font-size:.72rem;font-weight:700;color:#fff;flex-shrink:0;margin-top:1px;
        }
        .lp-step-txt{font-size:.8rem;color:rgba(255,255,255,.6);line-height:1.5;}
        .lp-step-title{font-size:.85rem;font-weight:600;color:rgba(255,255,255,.9);margin-bottom:2px;}

        /* ── Right / scroll panel ── */
        .rpanel{flex:1;overflow-y:auto;display:flex;justify-content:center;padding:48px 28px 64px;}
        .form-wrap{width:100%;max-width:520px;}

        /* Mobile brand */
        .mob-brand{display:flex;align-items:center;gap:14px;margin-bottom:32px;}
        .mob-seal{width:52px;height:52px;border-radius:50%;overflow:hidden;border:2px solid var(--accent-border);background:#fff;flex-shrink:0;}
        .mob-seal img{width:100%;height:100%;object-fit:cover;display:block;}
        .mob-app{font-size:1.15rem;font-weight:800;letter-spacing:-.4px;color:var(--accent);}
        .mob-uni{font-size:.7rem;color:var(--text-muted);margin-top:2px;}

        /* ── Page heading ── */
        .pg-title{font-size:2rem;font-weight:800;letter-spacing:-.6px;color:var(--text);margin-bottom:6px;}
        .pg-sub{font-size:.9rem;color:var(--text-muted);margin-bottom:36px;line-height:1.5;}

        /* ── Error alert ── */
        .alert-danger{
            background:var(--danger-bg);color:var(--danger);
            border:1px solid var(--danger-border);
            border-radius:12px;padding:12px 16px;
            font-size:.85rem;margin-bottom:24px;
            display:flex;align-items:center;gap:10px;
        }
        .alert-danger svg{width:16px;height:16px;flex-shrink:0;}

        /* ── Section headers ── */
        .section-hdr{
            display:flex;align-items:center;gap:12px;
            margin-bottom:18px;
        }
        .section-num{
            width:28px;height:28px;border-radius:50%;
            background:var(--accent-light);border:1.5px solid var(--accent-border);
            display:flex;align-items:center;justify-content:center;
            font-size:.75rem;font-weight:700;color:var(--accent-mid);flex-shrink:0;
        }
        .section-title{font-size:1rem;font-weight:700;color:var(--text);}
        .section-sub{font-size:.78rem;color:var(--text-muted);margin-top:1px;}

        /* ── Section box ── */
        .section-box{
            background:var(--surface);
            border:1px solid var(--border);
            border-radius:24px;
            padding:32px;
            margin-bottom:16px;
            box-shadow:0 8px 32px rgba(0,0,0,.04);
        }

        /* ── Fields ── */
        .field{margin-bottom:16px;}
        .field:last-child{margin-bottom:0;}
        .field label{
            display:block;font-size:.72rem;font-weight:600;
            letter-spacing:.07em;text-transform:uppercase;
            color:var(--text-soft);margin-bottom:7px;
        }
        .field input{
            width:100%;padding:14px 16px;
            font-family:'Plus Jakarta Sans',sans-serif;font-size:.95rem;font-weight:600;
            background:var(--surface2);
            border:1.5px solid var(--border2);
            border-radius:14px;color:var(--text);outline:none;
            transition:border-color .2s,box-shadow .2s,background .2s;
        }
        .field input::placeholder{color:var(--text-muted);}
        .field input:focus{
            border-color:var(--accent-mid);
            background:var(--surface);
            box-shadow:0 0 0 3px rgba(37,99,235,.1);
        }
        .field input.is-invalid{border-color:var(--danger);}
        .invalid-feedback{font-size:.77rem;color:var(--danger);margin-top:5px;display:flex;align-items:center;gap:5px;}
        .field-hint{font-size:.75rem;color:var(--text-muted);margin-top:5px;}

        .field-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}

        /* ID input */
        .id-wrap{position:relative;}
        .id-wrap input{padding-right:106px;}
        .id-badge{
            position:absolute;right:12px;top:50%;transform:translateY(-50%);
            font-size:.64rem;font-weight:700;letter-spacing:.05em;
            color:var(--text-muted);background:var(--bg);
            border:1px solid var(--border);
            padding:3px 9px;border-radius:6px;pointer-events:none;
        }

        /* Upload zone */
        .upload-zone{
            border:2px dashed var(--border2);border-radius:12px;
            padding:32px 20px;text-align:center;cursor:pointer;
            transition:border-color .2s,background .2s;
            position:relative;background:var(--surface2);
        }
        .upload-zone:hover,.upload-zone.drag-over{border-color:var(--accent-mid);background:var(--accent-light);}
        .upload-zone.has-file{border-color:var(--accent-mid);background:var(--accent-light);padding:20px;}
        .upload-zone.is-invalid{border-color:var(--danger);}
        .upload-zone input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
        .upload-icon-wrap{
            width:48px;height:48px;border-radius:12px;
            background:var(--surface);border:1.5px solid var(--accent-border);
            display:flex;align-items:center;justify-content:center;
            margin:0 auto 14px;
        }
        .upload-icon-wrap svg{width:22px;height:22px;color:var(--accent-mid);}
        .upload-ttl{font-size:.92rem;font-weight:600;color:var(--text-soft);margin-bottom:4px;}
        .upload-sub{font-size:.78rem;color:var(--text-muted);}
        .upload-chip{
            display:inline-flex;align-items:center;gap:6px;
            background:var(--surface);border:1px solid var(--accent-border);
            color:var(--accent-mid);font-size:.73rem;font-weight:600;
            padding:4px 12px;border-radius:99px;margin-top:10px;
        }
        .upload-chip svg{width:13px;height:13px;}

        .upload-preview{display:none;flex-direction:column;align-items:center;gap:12px;}
        .upload-preview.show{display:flex;}
        .preview-img-wrap{position:relative;}
        .preview-thumb{width:100%;max-width:200px;height:130px;object-fit:cover;border-radius:10px;border:2px solid var(--accent-border);box-shadow:0 4px 16px rgba(0,0,0,.08);}
        .preview-meta{font-size:.8rem;font-weight:600;color:var(--accent);text-align:center;}
        .preview-size{font-size:.73rem;color:var(--text-muted);text-align:center;margin-top:2px;}
        .change-link{font-size:.75rem;color:var(--accent-mid);cursor:pointer;text-decoration:underline;margin-top:4px;}

        /* Password strength */
        .str-wrap{margin-top:-8px;margin-bottom:16px;display:none;}
        .str-bars{display:flex;gap:4px;margin-bottom:5px;}
        .str-bar{flex:1;height:4px;background:var(--border);border-radius:99px;transition:background .3s;}
        .str-lbl{font-size:.75rem;font-weight:500;}

        /* Password match indicator */
        .match-indicator{
            display:none;align-items:center;gap:6px;
            font-size:.76rem;margin-top:6px;
        }
        .match-indicator.show{display:flex;}
        .match-indicator svg{width:14px;height:14px;}
        .match-ok{color:#16a34a;}
        .match-err{color:var(--danger);}

        /* ── Submit section ── */
        .submit-section{margin-top:8px;}
        .sbtn{
            width:100%;padding:14px;
            background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 100%);
            color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:1.05rem;
            border:none;border-radius:99px;cursor:pointer;
            box-shadow:0 4px 20px rgba(30,58,138,.25);
            transition:opacity .18s,transform .15s,box-shadow .18s;
            letter-spacing:.01em;
        }
        .sbtn:hover{opacity:.93;transform:translateY(-2px);box-shadow:0 6px 28px rgba(30,58,138,.35);}
        .sbtn:active{transform:translateY(0) scale(.98);}

        /* Review notice */
        .notice{
            display:flex;align-items:flex-start;gap:12px;
            background:var(--accent-light);border:1.5px solid var(--accent-border);
            border-radius:12px;padding:14px 16px;margin-top:14px;
        }
        .notice-icon{
            width:34px;height:34px;border-radius:9px;
            background:var(--surface);border:1px solid var(--accent-border);
            display:flex;align-items:center;justify-content:center;flex-shrink:0;
        }
        .notice-icon svg{width:17px;height:17px;color:var(--accent-mid);}
        .notice-title{font-size:.83rem;font-weight:600;color:var(--accent);margin-bottom:2px;}
        .notice-body{font-size:.77rem;color:var(--text-soft);line-height:1.5;}

        /* Footer */
        .auth-footer{text-align:center;margin-top:24px;font-size:.88rem;color:var(--text-muted);}
        .auth-footer a{color:var(--accent-mid);font-weight:600;text-decoration:none;}
        .auth-footer a:hover{text-decoration:underline;}

        /* ── Responsive ── */
        @media(min-width:900px){
            .lpanel{display:flex;}
            .mob-brand{display:none;}
        }
        @media(max-width:560px){
            .rpanel{padding:28px 16px 56px;}
            .section-box{padding:18px;}
            .field-row{grid-template-columns:1fr;}
            .pg-title{font-size:1.6rem;}
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ── Left panel ── --}}
    <div class="lpanel">
        <div class="lp-seal">
            <img src="{{ asset('storage/udd-logo.jpg') }}" alt="Universidad de Dagupan">
        </div>
        <div class="lp-app">UDDSafeSpaces</div>
        <div class="lp-uni">Universidad de Dagupan</div>
        <div class="lp-divider"></div>
        <div class="lp-steps">
            <div class="lp-step">
                <div class="lp-step-num">1</div>
                <div><div class="lp-step-title">Fill in your details</div><div class="lp-step-txt">Personal info, student ID and a photo of your school ID.</div></div>
            </div>
            <div class="lp-step">
                <div class="lp-step-num">2</div>
                <div><div class="lp-step-title">Wait for approval</div><div class="lp-step-txt">An admin will review and approve your registration.</div></div>
            </div>
            <div class="lp-step">
                <div class="lp-step-num">3</div>
                <div><div class="lp-step-title">Access the app</div><div class="lp-step-txt">Check space availability and scan QR codes to check in.</div></div>
            </div>
        </div>
    </div>

    {{-- ── Right / form ── --}}
    <div class="rpanel">
        <div class="form-wrap">

            {{-- Mobile brand --}}
            <div class="mob-brand">
                <div class="mob-seal"><img src="{{ asset('storage/udd-logo.jpg') }}" alt="UDD Logo"></div>
                <div>
                    <div class="mob-app">UDDSafeSpaces</div>
                    <div class="mob-uni">Universidad de Dagupan</div>
                </div>
            </div>

            <h1 class="pg-title">Create your account.</h1>
            <p class="pg-sub">Register as a UDD student to start using UDDSafeSpaces.</p>

            @if($errors->any())
            <div class="alert-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data">
                @csrf

                {{-- Section 1: Personal Info --}}
                <div class="section-hdr">
                    <div class="section-num">1</div>
                    <div>
                        <div class="section-title">Personal Information</div>
                        <div class="section-sub">Your name and contact details</div>
                    </div>
                </div>
                <div class="section-box">
                    <div class="field-row">
                        <div class="field">
                            <label>First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}"
                                   placeholder="Juan"
                                   class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="field">
                            <label>Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}"
                                   placeholder="dela Cruz"
                                   class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="field">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="you@udd.edu.ph"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Section 2: Student ID --}}
                <div class="section-hdr">
                    <div class="section-num">2</div>
                    <div>
                        <div class="section-title">Student ID</div>
                        <div class="section-sub">Your university-issued ID number and photo</div>
                    </div>
                </div>
                <div class="section-box">
                    <div class="field">
                        <label>Student ID Number</label>
                        <div class="id-wrap">
                            <input type="text" id="sid" name="student_id"
                                   value="{{ old('student_id') }}"
                                   placeholder="23-0066-858"
                                   maxlength="11"
                                   class="{{ $errors->has('student_id') ? 'is-invalid' : '' }}" required>
                            <span class="id-badge">##-####-###</span>
                        </div>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint">Format: two digits · four digits · three digits (e.g. 23-0066-858)</div>
                        @enderror
                    </div>
                    <div class="field" style="margin-bottom:0;">
                        <label>Front of Student ID <span style="color:var(--danger);">*</span></label>
                        <div class="upload-zone {{ $errors->has('id_image') ? 'is-invalid' : '' }}"
                             id="uploadZone">
                            <input type="file" name="id_image" id="idImage"
                                   accept="image/jpeg,image/png,image/jpg">
                            {{-- Default state --}}
                            <div id="uploadDefault">
                                <div class="upload-icon-wrap">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                                        <circle cx="8.5" cy="10.5" r="1.5"/>
                                        <path d="M21 15l-5-5L5 21"/>
                                    </svg>
                                </div>
                                <div class="upload-ttl">Click or drag your ID photo here</div>
                                <div class="upload-sub">Clear, well-lit photo · JPG or PNG · max 10MB</div>
                                <div class="upload-chip">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    Browse files
                                </div>
                            </div>
                            {{-- Preview state --}}
                            <div class="upload-preview" id="uploadPreview">
                                <img class="preview-thumb" id="previewThumb" src="" alt="ID preview">
                                <div class="preview-meta" id="previewName"></div>
                                <div class="preview-size" id="previewSize"></div>
                                <span class="change-link" id="changeLink">Change photo</span>
                            </div>
                        </div>
                        @error('id_image')
                            <div class="invalid-feedback" style="margin-top:6px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Section 3: Security --}}
                <div class="section-hdr">
                    <div class="section-num">3</div>
                    <div>
                        <div class="section-title">Set Your Password</div>
                        <div class="section-sub">Use at least 8 characters</div>
                    </div>
                </div>
                <div class="section-box">
                    <div class="field">
                        <label>Password</label>
                        <input type="password" id="pw" name="password"
                               placeholder="Choose a strong password"
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    {{-- Strength bars --}}
                    <div class="str-wrap" id="strWrap">
                        <div class="str-bars">
                            <div class="str-bar" id="sb1"></div>
                            <div class="str-bar" id="sb2"></div>
                            <div class="str-bar" id="sb3"></div>
                            <div class="str-bar" id="sb4"></div>
                            <div class="str-bar" id="sb5"></div>
                        </div>
                        <div class="str-lbl" id="strLbl"></div>
                    </div>
                    <div class="field" style="margin-bottom:0;">
                        <label>Confirm Password</label>
                        <input type="password" id="pwc" name="password_confirmation"
                               placeholder="Repeat your password" required>
                        <div class="match-indicator" id="matchOk">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="match-ok"><polyline points="20 6 9 17 4 12"/></svg>
                            <span class="match-ok">Passwords match</span>
                        </div>
                        <div class="match-indicator" id="matchErr">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="match-err"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            <span class="match-err">Passwords don't match</span>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="submit-section">
                    <button type="submit" class="sbtn">Submit Registration →</button>
                    <div class="notice">
                        <div class="notice-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="notice-title">Admin Review Required</div>
                            <div class="notice-body">Your registration will be reviewed by an administrator. You'll be able to sign in once approved.</div>
                        </div>
                    </div>
                </div>

            </form>

            <p class="auth-footer">Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
        </div>
    </div>
</div>

<script>
// ── Student ID formatter ──────────────────────────────────────────────────────
document.getElementById('sid').addEventListener('input', function() {
    let v = this.value.replace(/\D/g, ''), o = '';
    if (v.length > 0) o += v.substring(0, 2);
    if (v.length > 2) o += '-' + v.substring(2, 6);
    if (v.length > 6) o += '-' + v.substring(6, 9);
    this.value = o;
});

// ── File upload preview ───────────────────────────────────────────────────────
const fi   = document.getElementById('idImage');
const zone = document.getElementById('uploadZone');
const prev = document.getElementById('uploadPreview');
const def  = document.getElementById('uploadDefault');
const th   = document.getElementById('previewThumb');
const pn   = document.getElementById('previewName');
const ps   = document.getElementById('previewSize');

function handleFile(f) {
    if (!f) return;
    const r = new FileReader();
    r.onload = e => {
        th.src = e.target.result;
        pn.textContent = f.name;
        ps.textContent = (f.size / 1024 / 1024).toFixed(2) + ' MB';
        def.style.display = 'none';
        prev.classList.add('show');
        zone.classList.add('has-file');
    };
    r.readAsDataURL(f);
}

fi.addEventListener('change', () => handleFile(fi.files[0]));
zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('drag-over');
    const f = e.dataTransfer.files[0];
    if (f) { fi.files = e.dataTransfer.files; handleFile(f); }
});
document.getElementById('changeLink').addEventListener('click', e => {
    e.stopPropagation();
    fi.click();
});

// ── Password strength ─────────────────────────────────────────────────────────
const pw   = document.getElementById('pw');
const sw   = document.getElementById('strWrap');
const bars = [document.getElementById('sb1'),document.getElementById('sb2'),document.getElementById('sb3'),document.getElementById('sb4'),document.getElementById('sb5')];
const sl   = document.getElementById('strLbl');
const lvls = [
    { c: '#ef4444', t: 'Too short',  label: 'danger'  },
    { c: '#f97316', t: 'Weak',       label: 'warning' },
    { c: '#eab308', t: 'Fair',       label: 'warning' },
    { c: '#3b82f6', t: 'Good',       label: 'info'    },
    { c: '#16a34a', t: 'Strong ✓',   label: 'success' },
];
pw.addEventListener('input', () => {
    const v = pw.value;
    if (!v) { sw.style.display = 'none'; bars.forEach(b => b.style.background = ''); return; }
    sw.style.display = 'block';
    let s = 0;
    if (v.length >= 8)  s++;
    if (v.length >= 12) s++;
    if (/[A-Z]/.test(v)) s++;
    if (/[0-9]/.test(v)) s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;
    const l = lvls[Math.min(s, 4)];
    bars.forEach((b, i) => { b.style.background = i <= s - 1 ? l.c : 'var(--border)'; });
    sl.textContent  = l.t;
    sl.style.color  = l.c;
    sl.style.fontWeight = '600';
    checkMatch();
});

// ── Password match ────────────────────────────────────────────────────────────
const pwc      = document.getElementById('pwc');
const matchOk  = document.getElementById('matchOk');
const matchErr = document.getElementById('matchErr');
function checkMatch() {
    if (!pwc.value) { matchOk.classList.remove('show'); matchErr.classList.remove('show'); return; }
    const ok = pw.value === pwc.value;
    matchOk.classList.toggle('show', ok);
    matchErr.classList.toggle('show', !ok);
}
pwc.addEventListener('input', checkMatch);
</script>
</body>
</html>