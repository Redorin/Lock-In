<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>UDDSafeSpaces — Resubmit Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ═══════════════════════════════════════════
           THEME TOKENS (mirrored from student layout)
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
            --sidebar: rgba(15, 23, 42, 0.6);
            --modal: rgba(15, 23, 42, 0.85);
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
            --sidebar: rgba(255,255,255,.92);
            --modal: #ffffff;
            --inset: inset 0 1px 0 rgba(255,255,255,.8);
            --shadow-sm: 0 2px 8px rgba(0,0,0,.06);
            --shadow-md: 0 8px 24px rgba(0,0,0,.08);
            --shadow-lg: 0 20px 48px rgba(0,0,0,.1);
        }

        /* ═══════════════════════════════════════════
           BASE
        ═══════════════════════════════════════════ */
        *,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { background: var(--bg); scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg); color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            transition: background var(--t) var(--ease), color var(--t) var(--ease);
        }

        /* Ambient mesh */
        body::before {
            content: ''; position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 60% 50% at 10% 10%, var(--accent-bg) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 90% 80%, rgba(139,92,246,.06) 0%, transparent 60%);
            transition: opacity var(--t) var(--ease);
        }

        /* ═══════════════════════════════════════════
           TOPBAR
        ═══════════════════════════════════════════ */
        .topbar {
            position: sticky; top: 0; z-index: 50;
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 20px;
            background: var(--sidebar);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            transition: background var(--t) var(--ease);
        }
        .tb-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .tb-logo { width: 34px; height: 34px; border-radius: 9px; overflow: hidden; flex-shrink: 0; }
        .tb-logo img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .tb-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .95rem; font-weight: 800; letter-spacing: -.3px; color: var(--text);
        }
        .tb-right { display: flex; align-items: center; gap: 8px; }
        .tb-theme {
            width: 36px; height: 36px; background: var(--surface2); border: 1px solid var(--border);
            border-radius: 9px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-soft); transition: all var(--t) var(--ease);
        }
        .tb-theme:hover { background: var(--accent-bg); color: var(--accent2); border-color: var(--accent-border); }
        .tb-theme svg { width: 17px; height: 17px; }
        .icon-sun { display: none; }
        .icon-moon { display: block; }
        [data-theme="light"] .icon-sun { display: block; }
        [data-theme="light"] .icon-moon { display: none; }

        .logout-btn {
            display: flex; align-items: center; gap: 6px;
            background: var(--danger-bg); color: var(--danger);
            border: 1px solid var(--danger-border);
            padding: 7px 14px; border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .8rem; font-weight: 700; cursor: pointer;
            text-decoration: none; transition: all var(--t) var(--ease);
        }
        .logout-btn:hover { background: var(--danger); color: #fff; }
        .logout-btn svg { width: 14px; height: 14px; }

        /* ═══════════════════════════════════════════
           PAGE SHELL
        ═══════════════════════════════════════════ */
        .page { min-height: calc(100vh - 64px); display: flex; justify-content: center; padding: 32px 20px 72px; position: relative; z-index: 1; }
        .form-wrap { width: 100%; max-width: 560px; }

        /* Page header */
        .pg-hdr { margin-bottom: 28px; animation: pageIn .5s var(--ease) both; }
        .pg-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.8rem; font-weight: 800; letter-spacing: -.6px;
            color: var(--text); margin-bottom: 6px;
        }
        .pg-sub {
            font-size: .88rem; color: var(--text-soft);
            line-height: 1.65; max-width: 440px;
        }
        @keyframes pageIn { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

        /* ═══════════════════════════════════════════
           REJECTION NOTICE
        ═══════════════════════════════════════════ */
        .rejection-banner {
            background: var(--danger-bg); border: 1px solid var(--danger-border);
            border-radius: 16px; padding: 16px 18px;
            display: flex; align-items: flex-start; gap: 12px;
            margin-bottom: 24px;
            animation: pageIn .5s .06s var(--ease) both;
        }
        .rejection-banner svg { width: 18px; height: 18px; color: var(--danger); flex-shrink: 0; margin-top: 1px; }
        .rejection-banner-body { font-size: .88rem; color: var(--danger); line-height: 1.55; }
        .rejection-banner-body strong { display: block; font-size: .72rem; font-weight: 800; letter-spacing: .07em; text-transform: uppercase; margin-bottom: 4px; }

        .error-banner {
            background: var(--danger-bg); border: 1px solid var(--danger-border);
            border-radius: 12px; padding: 13px 16px;
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 20px; font-size: .875rem; color: var(--danger);
        }
        .error-banner svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* ═══════════════════════════════════════════
           FORM CARD
        ═══════════════════════════════════════════ */
        .form-card {
            background: var(--surface); border: 1px solid var(--border2);
            border-radius: 24px; padding: 28px;
            box-shadow: var(--shadow-md), var(--inset);
            margin-bottom: 16px;
            animation: pageIn .5s .12s var(--ease) both;
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            transition: background var(--t) var(--ease), border-color var(--t) var(--ease);
        }

        .fsect-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .72rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
            color: var(--text-muted); margin-bottom: 18px; padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .fsect-icon {
            width: 28px; height: 28px; border-radius: 8px;
            background: var(--accent-bg); border: 1px solid var(--accent-border);
            display: flex; align-items: center; justify-content: center;
        }
        .fsect-icon svg { width: 14px; height: 14px; color: var(--accent2); }

        .field { margin-bottom: 18px; }
        .field:last-child { margin-bottom: 0; }
        .field label {
            display: block; font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .8rem; font-weight: 700; color: var(--text-soft); margin-bottom: 8px;
        }
        .field input {
            width: 100%; padding: 13px 16px;
            font-family: 'Inter', sans-serif; font-size: .95rem;
            background: var(--surface2); border: 1.5px solid var(--border2);
            border-radius: 12px; color: var(--text); outline: none;
            transition: all var(--t) var(--ease);
        }
        .field input::placeholder { color: var(--text-muted); }
        .field input:focus {
            border-color: var(--accent); background: var(--surface);
            box-shadow: 0 0 0 3px var(--accent-bg);
        }
        .field input.is-invalid { border-color: var(--danger); background: var(--danger-bg); }
        .invalid-feedback { font-size: .77rem; color: var(--danger); margin-top: 5px; }
        .field-hint { font-size: .75rem; color: var(--text-muted); margin-top: 5px; }

        /* ID input */
        .id-wrap { position: relative; }
        .id-wrap input { padding-right: 110px; }
        .id-badge {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            font-size: .64rem; font-weight: 700; letter-spacing: .05em;
            color: var(--text-muted); background: var(--surface3);
            border: 1px solid var(--border2);
            padding: 3px 9px; border-radius: 6px; pointer-events: none;
        }

        /* Upload zone */
        .upload-zone {
            border: 2px dashed var(--border2); border-radius: 14px;
            padding: 32px 20px; text-align: center; cursor: pointer;
            transition: border-color .2s, background .2s;
            position: relative; background: var(--surface2);
        }
        .upload-zone:hover, .upload-zone.drag-over {
            border-color: var(--accent); background: var(--accent-bg);
        }
        .upload-zone.has-file { border-color: var(--accent); background: var(--accent-bg); padding: 20px; }
        .upload-zone.is-invalid { border-color: var(--danger); }
        .upload-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .upload-icon-wrap {
            width: 48px; height: 48px; border-radius: 12px;
            background: var(--surface); border: 1px solid var(--accent-border);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }
        .upload-icon-wrap svg { width: 22px; height: 22px; color: var(--accent2); }
        .upload-ttl { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .9rem; font-weight: 600; color: var(--text-soft); margin-bottom: 4px; }
        .upload-sub { font-size: .78rem; color: var(--text-muted); }
        .upload-chip {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--accent-bg); border: 1px solid var(--accent-border);
            color: var(--accent2); font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .73rem; font-weight: 700;
            padding: 5px 14px; border-radius: 99px; margin-top: 12px;
        }

        .upload-preview { display: none; flex-direction: column; align-items: center; gap: 12px; }
        .upload-preview.show { display: flex; }
        .preview-thumb { width: 100%; max-width: 200px; height: 130px; object-fit: cover; border-radius: 10px; border: 2px solid var(--accent-border); box-shadow: var(--shadow-sm); }
        .preview-meta { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .8rem; font-weight: 700; color: var(--accent2); text-align: center; }
        .change-link { font-size: .75rem; color: var(--accent2); cursor: pointer; text-decoration: underline; margin-top: 2px; }

        /* ═══════════════════════════════════════════
           SUBMIT BUTTON
        ═══════════════════════════════════════════ */
        .submit-card {
            animation: pageIn .5s .18s var(--ease) both;
        }
        .sbtn {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, var(--accent), #6366f1); color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1rem;
            border: none; border-radius: 99px; cursor: pointer;
            box-shadow: 0 4px 20px var(--accent-glow);
            transition: all var(--t) var(--ease);
            letter-spacing: .01em; position: relative; overflow: hidden;
        }
        .sbtn::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,.12) 0%, transparent 100%);
            pointer-events: none;
        }
        .sbtn:hover { transform: translateY(-2px); box-shadow: 0 6px 28px var(--accent-glow); }
        .sbtn:active { transform: translateY(0) scale(.98); }
    </style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
    <a href="#" class="tb-brand">
        <div class="tb-logo">
            <img src="{{ asset('storage/udd-logo.jpg') }}" alt="UDD"
                 onerror="this.style.display='none';this.parentElement.style.background='linear-gradient(135deg,#3b82f6,#6366f1)'">
        </div>
        <span class="tb-name">UDDSafeSpaces</span>
    </a>
    <div class="tb-right">
        <button class="tb-theme" onclick="toggleTheme()" title="Toggle theme">
            <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            <svg class="icon-sun"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        </button>
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="logout-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</div>

<div class="page">
    <div class="form-wrap">

        <div class="pg-hdr">
            <h1 class="pg-title">Registration Rejected</h1>
            <p class="pg-sub">Your student registration was not approved. Please review the reason below and resubmit your details.</p>
        </div>

        {{-- Rejection reason --}}
        <div class="rejection-banner">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div class="rejection-banner-body">
                <strong>Rejection Reason</strong>
                {{ $user->rejection_reason ?? 'Please verify your details and upload a valid student ID.' }}
            </div>
        </div>

        @if($errors->any())
        <div class="error-banner">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('student.resubmit.post') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-card">
                <div class="fsect-title">
                    <div class="fsect-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    Personal Information
                </div>

                <div class="field">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                           placeholder="Your full name" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Student ID Number</label>
                    <div class="id-wrap">
                        <input type="text" id="sid" name="student_id"
                               value="{{ old('student_id', $user->student_id) }}"
                               maxlength="11" placeholder="00-0000-000"
                               class="{{ $errors->has('student_id') ? 'is-invalid' : '' }}" required>
                        <span class="id-badge">##-####-###</span>
                    </div>
                    @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="field" style="margin-bottom:0;">
                    <label>New ID Photo <span style="color:var(--danger);">*</span></label>
                    <div class="upload-zone {{ $errors->has('id_image') ? 'is-invalid' : '' }}" id="uploadZone">
                        <input type="file" name="id_image" id="idImage" accept="image/jpeg,image/png,image/jpg" required>
                        <div id="uploadDefault">
                            <div class="upload-icon-wrap">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <circle cx="8.5" cy="10.5" r="1.5"/>
                                    <path d="M21 15l-5-5L5 21"/>
                                </svg>
                            </div>
                            <div class="upload-ttl">Upload your new ID photo here</div>
                            <div class="upload-sub">Clear, well-lit photo · JPG or PNG</div>
                            <div class="upload-chip">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Browse Files
                            </div>
                        </div>
                        <div class="upload-preview" id="uploadPreview">
                            <img class="preview-thumb" id="previewThumb" src="" alt="ID preview">
                            <div class="preview-meta" id="previewName"></div>
                            <div class="change-link" id="changeLink">Change photo</div>
                        </div>
                    </div>
                    @error('id_image')<div class="invalid-feedback" style="margin-top:6px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="submit-card">
                <button type="submit" class="sbtn">Submit Application for Review →</button>
            </div>
        </form>

    </div>
</div>

<script>
// Theme
const html = document.documentElement;
function applyTheme(t) {
    html.setAttribute('data-theme', t);
    localStorage.setItem('uddss_theme', t);
}
function toggleTheme() { applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'); }
(function(){ applyTheme(localStorage.getItem('uddss_theme') || 'dark'); })();

// Student ID formatting
document.getElementById('sid').addEventListener('input', function() {
    let v = this.value.replace(/\D/g, ''), o = '';
    if (v.length > 0) o += v.substring(0, 2);
    if (v.length > 2) o += '-' + v.substring(2, 6);
    if (v.length > 6) o += '-' + v.substring(6, 9);
    this.value = o;
});

// Upload handling
const fi   = document.getElementById('idImage');
const zone = document.getElementById('uploadZone');
const prev = document.getElementById('uploadPreview');
const def  = document.getElementById('uploadDefault');
const th   = document.getElementById('previewThumb');
const pn   = document.getElementById('previewName');

function handleFile(f) {
    if (!f) return;
    const r = new FileReader();
    r.onload = e => {
        th.src = e.target.result;
        pn.textContent = f.name;
        def.style.display = 'none';
        prev.classList.add('show');
        zone.classList.add('has-file');
    };
    r.readAsDataURL(f);
}

fi.addEventListener('change', () => handleFile(fi.files[0]));
zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
zone.addEventListener('dragleave', ()  => zone.classList.remove('drag-over'));
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('drag-over');
    if (e.dataTransfer.files[0]) { fi.files = e.dataTransfer.files; handleFile(fi.files[0]); }
});
document.getElementById('changeLink').addEventListener('click', e => {
    e.stopPropagation(); fi.click();
});
</script>
</body>
</html>
