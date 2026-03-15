<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampuSIMS — Create Account</title>
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
                radial-gradient(ellipse 70% 60% at 10% 10%, rgba(0,229,160,.14) 0%, transparent 55%),
                radial-gradient(ellipse 50% 50% at 85% 85%, rgba(124,111,247,.12) 0%, transparent 55%),
                radial-gradient(ellipse 40% 35% at 60% 20%, rgba(200,240,77,.06) 0%, transparent 50%);
        }

        .page {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 40px 20px;
        }

        .auth-wrap { width: 100%; max-width: 500px; }

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
        .alert-danger { background: rgba(255,112,112,.08); color: var(--danger); border: 1px solid rgba(255,112,112,.18); }

        .section-label {
            font-size: .68rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: var(--text-muted);
            margin: 20px 0 14px;
            display: flex; align-items: center; gap: 10px;
        }
        .section-label::after { content: ''; flex: 1; height: 1px; background: var(--glass-border); }

        .field { margin-bottom: 14px; }
        .field label {
            display: block;
            font-size: .71rem; font-weight: 600;
            letter-spacing: .08em; text-transform: uppercase;
            color: var(--text-muted); margin-bottom: 7px;
        }
        .field input[type=text],
        .field input[type=email],
        .field input[type=password] {
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
        .field-hint { font-size: .73rem; color: var(--text-muted); margin-top: 5px; }

        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .id-input-wrap { position: relative; }
        .id-input-wrap input { padding-right: 88px; }
        .id-format-badge {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            font-size: .65rem; font-weight: 700; letter-spacing: .04em;
            color: var(--text-muted);
            background: rgba(255,255,255,.06);
            border: 1px solid var(--glass-border);
            padding: 3px 8px; border-radius: 6px;
            pointer-events: none;
        }

        .upload-zone {
            border: 1.5px dashed var(--glass-border);
            border-radius: var(--radius-md);
            padding: 24px 16px;
            text-align: center; cursor: pointer;
            transition: border-color .2s, background .2s;
            position: relative;
            background: rgba(255,255,255,.02);
        }
        .upload-zone:hover, .upload-zone.drag-over {
            border-color: rgba(0,229,160,.4);
            background: rgba(0,229,160,.03);
        }
        .upload-zone.has-file { border-color: rgba(0,229,160,.35); background: rgba(0,229,160,.04); }
        .upload-zone.is-invalid { border-color: rgba(255,112,112,.4); }
        .upload-zone input[type=file] {
            position: absolute; inset: 0;
            opacity: 0; cursor: pointer; width: 100%; height: 100%;
        }
        .upload-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: rgba(0,229,160,.08); border: 1px solid rgba(0,229,160,.15);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 10px;
        }
        .upload-icon svg { width: 18px; height: 18px; color: var(--accent); }
        .upload-title { font-size: .85rem; font-weight: 600; margin-bottom: 4px; }
        .upload-sub   { font-size: .75rem; color: var(--text-muted); }

        .upload-preview {
            display: none; align-items: center; gap: 12px;
            background: rgba(0,229,160,.06); border-radius: var(--radius-sm);
            padding: 10px 14px; margin-top: 12px; text-align: left;
        }
        .upload-preview.show { display: flex; }
        .preview-thumb { width: 48px; height: 36px; border-radius: 6px; object-fit: cover; border: 1px solid rgba(0,229,160,.2); flex-shrink: 0; }
        .preview-name { font-size: .78rem; font-weight: 500; color: var(--accent); }
        .preview-size { font-size: .7rem; color: var(--text-muted); }

        .strength-wrap { margin-top: -6px; margin-bottom: 14px; display: none; }
        .strength-track { height: 3px; background: rgba(255,255,255,.07); border-radius: 99px; overflow: hidden; }
        .strength-fill  { height: 100%; border-radius: 99px; transition: width .3s, background .3s; }
        .strength-label { font-size: .72rem; margin-top: 5px; }

        .btn-submit {
            width: 100%; padding: 13px;
            background: var(--accent); color: #091510;
            font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem;
            border: none; border-radius: var(--radius-md); cursor: pointer;
            box-shadow: 0 4px 24px rgba(0,229,160,.25);
            transition: opacity .18s, transform .15s; margin-top: 8px;
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

        .notice-box {
            background: rgba(0,229,160,.06); border: 1px solid rgba(0,229,160,.15);
            border-radius: var(--radius-sm); padding: 10px 14px;
            font-size: .78rem; color: var(--text-soft);
            display: flex; align-items: flex-start; gap: 10px; margin-top: 16px;
        }
        .notice-box svg { width: 15px; height: 15px; color: var(--accent); flex-shrink: 0; margin-top: 1px; }
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
            <h1 class="auth-title">Create account.</h1>
            <p class="auth-sub">Join CampuSIMS — fill in your details to register.</p>

            @if($errors->any())
                <div class="alert alert-danger">Please fix the errors below and try again.</div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data">
                @csrf

                <div class="section-label">Personal Info</div>

                <div class="field-row">
                    <div class="field">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" name="first_name"
                               value="{{ old('first_name') }}" placeholder="Juan"
                               class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}" required>
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label for="last_name">Last name</label>
                        <input type="text" id="last_name" name="last_name"
                               value="{{ old('last_name') }}" placeholder="dela Cruz"
                               class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}" required>
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}" placeholder="you@school.edu"
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="section-label">Student ID</div>

                <div class="field">
                    <label for="student_id">Student ID Number</label>
                    <div class="id-input-wrap">
                        <input type="text" id="student_id" name="student_id"
                               value="{{ old('student_id') }}"
                               placeholder="23-0066-858"
                               maxlength="11"
                               class="{{ $errors->has('student_id') ? 'is-invalid' : '' }}"
                               required>
                        <span class="id-format-badge">##-####-###</span>
                    </div>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="field-hint">Format: two digits · four digits · three digits &nbsp;(e.g. 23-0066-858)</div>
                    @enderror
                </div>

                <div class="field">
                    <label>Front of Student ID <span style="color:var(--danger);font-size:.8rem;">*</span></label>
                    <div class="upload-zone {{ $errors->has('id_image') ? 'is-invalid' : '' }}" id="uploadZone">
                        <input type="file" name="id_image" id="idImage" accept="image/jpeg,image/png,image/jpg">
                        <div id="uploadDefault">
                            <div class="upload-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <circle cx="8.5" cy="10.5" r="1.5"/>
                                    <path d="M21 15l-5-5L5 21"/>
                                </svg>
                            </div>
                            <div class="upload-title">Click or drag your ID photo here</div>
                            <div class="upload-sub">JPG or PNG · max 10MB</div>
                        </div>
                        <div class="upload-preview" id="uploadPreview">
                            <img class="preview-thumb" id="previewThumb" src="" alt="preview">
                            <div>
                                <div class="preview-name" id="previewName"></div>
                                <div class="preview-size" id="previewSize"></div>
                            </div>
                        </div>
                    </div>
                    @error('id_image') <div class="invalid-feedback" style="margin-top:6px;">{{ $message }}</div> @enderror
                </div>

                <div class="section-label">Security</div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="Min. 8 characters"
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="strength-wrap" id="strengthWrap">
                    <div class="strength-track">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <div class="strength-label" id="strengthLabel"></div>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           placeholder="Repeat password" required>
                </div>

                <button type="submit" class="btn-submit">Submit Registration →</button>

                <div class="notice-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    Your account will be reviewed by an admin before you can log in.
                </div>
            </form>
        </div>

        <p class="auth-footer">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
        </p>

    </div>
</div>

<script>
// ── Student ID auto-format: ##-####-### ───────────────────────────────────────
const studentId = document.getElementById('student_id');
studentId.addEventListener('input', function () {
    let val = this.value.replace(/\D/g, '');
    let out = '';
    if (val.length > 0) out += val.substring(0, 2);
    if (val.length > 2) out += '-' + val.substring(2, 6);
    if (val.length > 6) out += '-' + val.substring(6, 9);
    this.value = out;
});

// ── File upload preview ───────────────────────────────────────────────────────
const fileInput  = document.getElementById('idImage');
const zone       = document.getElementById('uploadZone');
const preview    = document.getElementById('uploadPreview');
const defContent = document.getElementById('uploadDefault');
const thumb      = document.getElementById('previewThumb');
const prevName   = document.getElementById('previewName');
const prevSize   = document.getElementById('previewSize');

function handleFile(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        thumb.src = e.target.result;
        prevName.textContent = file.name;
        prevSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        defContent.style.display = 'none';
        preview.classList.add('show');
        zone.classList.add('has-file');
    };
    reader.readAsDataURL(file);
}

fileInput.addEventListener('change', () => handleFile(fileInput.files[0]));
zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
zone.addEventListener('drop', e => {
    e.preventDefault(); zone.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file) { fileInput.files = e.dataTransfer.files; handleFile(file); }
});

// ── Password strength ─────────────────────────────────────────────────────────
const pw     = document.getElementById('password');
const wrap   = document.getElementById('strengthWrap');
const fill   = document.getElementById('strengthFill');
const lbl    = document.getElementById('strengthLabel');
const levels = [
    { color: '#ff7070', text: 'Too short'  },
    { color: '#fb923c', text: 'Weak'       },
    { color: '#fbbf24', text: 'Fair'       },
    { color: '#a3e635', text: 'Good'       },
    { color: '#00e5a0', text: 'Strong'     },
];
pw.addEventListener('input', () => {
    const v = pw.value;
    if (!v) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'block';
    let s = 0;
    if (v.length >= 8)           s++;
    if (v.length >= 12)          s++;
    if (/[A-Z]/.test(v))         s++;
    if (/[0-9]/.test(v))         s++;
    if (/[^A-Za-z0-9]/.test(v))  s++;
    const lvl = levels[Math.min(s, 4)];
    fill.style.width      = ((s / 5) * 100) + '%';
    fill.style.background = lvl.color;
    lbl.textContent       = lvl.text;
    lbl.style.color       = lvl.color;
});
</script>
</body>
</html>