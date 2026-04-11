<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>UDDSafeSpaces — Resubmit Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

        /* Page Shell */
        .page{min-height:100vh;display:flex;justify-content:center;padding:48px 28px 64px;}
        .form-wrap{width:100%;max-width:560px;}

        /* Mobile brand */
        .mob-brand{display:flex;align-items:center;gap:14px;margin-bottom:32px;}
        .mob-seal{width:52px;height:52px;border-radius:50%;overflow:hidden;border:2px solid var(--accent-border);background:#fff;flex-shrink:0;}
        .mob-seal img{width:100%;height:100%;object-fit:cover;display:block;}
        .mob-app{font-size:1.15rem;font-weight:800;letter-spacing:-.4px;color:var(--accent);}
        .mob-uni{font-size:.7rem;color:var(--text-muted);margin-top:2px;}

        .pg-title{font-size:2rem;font-weight:800;letter-spacing:-.6px;color:var(--text);margin-bottom:6px;}
        .pg-sub{font-size:.9rem;color:var(--text-muted);margin-bottom:24px;line-height:1.5;}

        .alert-danger{
            background:var(--danger-bg);color:var(--danger);
            border:1px solid var(--danger-border);
            border-radius:12px;padding:16px;
            font-size:.9rem;margin-bottom:24px;
            display:flex;align-items:flex-start;gap:12px;
        }
        .alert-danger svg{width:20px;height:20px;flex-shrink:0;}

        /* Section box */
        .section-box{
            background:var(--surface);
            border:1px solid var(--border);
            border-radius:16px;
            padding:24px;
            margin-bottom:16px;
            box-shadow:0 2px 10px rgba(0,0,0,.05);
        }

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
            box-shadow:0 0 0 4px rgba(37,99,235,.15);
        }
        .field input.is-invalid{border-color:var(--danger);}
        .invalid-feedback{font-size:.77rem;color:var(--danger);margin-top:5px;display:flex;align-items:center;gap:5px;}
        .field-hint{font-size:.75rem;color:var(--text-muted);margin-top:5px;}

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

        .upload-preview{display:none;flex-direction:column;align-items:center;gap:12px;}
        .upload-preview.show{display:flex;}
        .preview-img-wrap{position:relative;}
        .preview-thumb{width:100%;max-width:200px;height:130px;object-fit:cover;border-radius:10px;border:2px solid var(--accent-border);box-shadow:0 4px 16px rgba(0,0,0,.08);}
        .preview-meta{font-size:.8rem;font-weight:600;color:var(--accent);text-align:center;}
        .preview-size{font-size:.73rem;color:var(--text-muted);text-align:center;margin-top:2px;}
        .change-link{font-size:.75rem;color:var(--accent-mid);cursor:pointer;text-decoration:underline;margin-top:4px;}

        /* Submit section */
        .submit-section{margin-top:16px;}
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

        /* Logout Top Right */
        .logout-btn {
            position:absolute; top:30px; right:30px;
            background:rgba(220,38,38,.1); color:var(--danger);
            border:1px solid rgba(220,38,38,.2);
            padding:6px 14px; border-radius:8px;
            font-size:.85rem; font-weight:600; cursor:pointer; text-decoration:none;
        }
        .logout-btn:hover { background:rgba(220,38,38,.15); }
    </style>
</head>
<body>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="logout-btn">Cancel &amp; Sign Out</button>
</form>

<div class="page">
    <div class="form-wrap">

        <div class="mob-brand">
            <div class="mob-seal"><img src="{{ asset('storage/udd-logo.jpg') }}" alt="UDD Logo"></div>
            <div>
                <div class="mob-app">UDDSafeSpaces</div>
                <div class="mob-uni">Universidad de Dagupan</div>
            </div>
        </div>

        <h1 class="pg-title">Registration Rejected</h1>
        <p class="pg-sub">Your student registration was not approved. Please review the reason below and resubmit your details.</p>

        <div class="alert-danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div>
                <strong>Rejection Reason:</strong><br>
                {{ $user->rejection_reason ?? 'Please verify your details and upload a valid student ID.' }}
            </div>
        </div>

        @if($errors->any())
        <div class="alert-danger" style="background:var(--danger-bg); border-color:var(--danger-border); color:var(--danger);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('student.resubmit.post') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="section-box">
                <div class="field">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="field">
                    <label>Student ID Number</label>
                    <div class="id-wrap">
                        <input type="text" id="sid" name="student_id"
                               value="{{ old('student_id', $user->student_id) }}"
                               maxlength="11"
                               class="{{ $errors->has('student_id') ? 'is-invalid' : '' }}" required>
                        <span class="id-badge">##-####-###</span>
                    </div>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field" style="margin-bottom:0;">
                    <label>New ID Photo <span style="color:var(--danger);">*</span></label>
                    <div class="upload-zone {{ $errors->has('id_image') ? 'is-invalid' : '' }}" id="uploadZone">
                        <input type="file" name="id_image" id="idImage" accept="image/jpeg,image/png,image/jpg" required>
                        <div id="uploadDefault">
                            <div class="upload-icon-wrap">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <circle cx="8.5" cy="10.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
                                </svg>
                            </div>
                            <div class="upload-ttl">Upload your new ID photo here</div>
                            <div class="upload-sub">Clear, well-lit photo · JPG or PNG</div>
                            <div class="upload-chip">Browse files</div>
                        </div>
                        <div class="upload-preview" id="uploadPreview">
                            <img class="preview-thumb" id="previewThumb" src="" alt="ID preview">
                            <div class="preview-meta" id="previewName"></div>
                            <div class="change-link" id="changeLink">Change photo</div>
                        </div>
                    </div>
                    @error('id_image')
                        <div class="invalid-feedback" style="margin-top:6px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="submit-section">
                <button type="submit" class="sbtn">Submit Application for Review →</button>
            </div>
        </form>

    </div>
</div>

<script>
document.getElementById('sid').addEventListener('input', function() {
    let v = this.value.replace(/\D/g, ''), o = '';
    if (v.length > 0) o += v.substring(0, 2);
    if (v.length > 2) o += '-' + v.substring(2, 6);
    if (v.length > 6) o += '-' + v.substring(6, 9);
    this.value = o;
});

const fi = document.getElementById('idImage');
const zone = document.getElementById('uploadZone');
const prev = document.getElementById('uploadPreview');
const def = document.getElementById('uploadDefault');
const th = document.getElementById('previewThumb');
const pn = document.getElementById('previewName');

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
zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
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
