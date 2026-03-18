@extends('student.layout')
@section('title','Settings')
@section('page-title','Settings')
@section('page-sub','Manage your account and preferences')

@section('styles')
<style>
    .sw { display:grid;grid-template-columns:260px 1fr;gap:16px;align-items:start; }

    /* Profile card */
    .pc {
        background:var(--surface);border:1px solid var(--border);border-radius:20px;
        padding:28px 24px;text-align:center;
        box-shadow:var(--shadow-sm),var(--inset);
        animation:cardIn .5s var(--ease) both;
        position:relative;overflow:hidden;
        transition:background var(--t) var(--ease);
    }
    .pc::before {
        content:'';position:absolute;top:-40px;left:50%;transform:translateX(-50%);
        width:180px;height:180px;border-radius:50%;
        background:radial-gradient(circle,var(--accent-bg) 0%,transparent 70%);
        pointer-events:none;
    }
    @keyframes cardIn{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}

    .big-av {
        width:76px;height:76px;border-radius:50%;
        background:linear-gradient(135deg,var(--accent),#6366f1);
        display:flex;align-items:center;justify-content:center;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:1.7rem;font-weight:800;color:#fff;
        margin:0 auto 14px;
        border:3px solid var(--accent-border);
        box-shadow:0 0 0 6px var(--accent-bg);
        position:relative;z-index:1;
        transition:box-shadow var(--t) var(--ease);
    }
    .big-av:hover{box-shadow:0 0 0 8px var(--accent-bg);}
    .pn{font-family:'Plus Jakarta Sans',sans-serif;font-size:1rem;font-weight:700;color:var(--text);margin-bottom:3px;position:relative;z-index:1;}
    .pe{font-size:.75rem;color:var(--text-muted);margin-bottom:16px;word-break:break-all;position:relative;z-index:1;}
    .pb{display:inline-block;background:var(--accent-bg);border:1px solid var(--accent-border);color:var(--accent2);font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:4px 12px;border-radius:99px;position:relative;z-index:1;}

    /* Divider in profile card */
    .pc-divider{height:1px;background:var(--border);margin:18px 0;position:relative;z-index:1;}

    .pc-stat{text-align:center;position:relative;z-index:1;}
    .pc-stat-val{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.1rem;font-weight:800;color:var(--text);}
    .pc-stat-lbl{font-size:.68rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;}

    /* Form card */
    .fc {
        background:var(--surface);border:1px solid var(--border);border-radius:20px;
        padding:28px;
        box-shadow:var(--shadow-sm),var(--inset);
        animation:cardIn .5s .06s var(--ease) both;
        transition:background var(--t) var(--ease);
    }
    .fst {
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
        color:var(--text-muted);margin-bottom:16px;padding-bottom:12px;
        border-bottom:1px solid var(--border);
        display:flex;align-items:center;gap:8px;
    }
    .fst-icon{width:24px;height:24px;border-radius:6px;background:var(--accent-bg);border:1px solid var(--accent-border);display:flex;align-items:center;justify-content:center;}
    .fst-icon svg{width:12px;height:12px;color:var(--accent2);}

    .fr { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
    .fdiv { height:1px;background:var(--border);margin:22px 0; }

    .bs {
        padding:11px 28px;
        background:linear-gradient(135deg,var(--accent),#6366f1);
        color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.9rem;
        border:none;border-radius:10px;cursor:pointer;
        box-shadow:0 4px 16px var(--accent-glow);
        transition:all var(--t) var(--ease);
    }
    .bs:hover{transform:translateY(-1px);box-shadow:0 6px 20px var(--accent-glow);}
    .bs:active{transform:translateY(0);}
    .bc {
        padding:11px 20px;background:transparent;color:var(--text-muted);
        font-family:'Plus Jakarta Sans',sans-serif;font-size:.9rem;
        border:1px solid var(--border2);border-radius:10px;cursor:pointer;
        transition:all var(--t) var(--ease);margin-left:10px;
    }
    .bc:hover{border-color:var(--border2);color:var(--text-soft);background:var(--surface2);}

    @media(max-width:768px){.sw{grid-template-columns:1fr;}.fr{grid-template-columns:1fr;}.fc,.pc{padding:20px;}.bs,.bc{width:100%;margin-left:0;margin-top:8px;display:block;}}
</style>
@endsection

@section('content')
<div class="sw">
    <div class="pc">
        <div class="big-av">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="pn">{{ $user->name }}</div>
        <div class="pe">{{ $user->email }}</div>
        <span class="pb">{{ $user->role }}</span>
        <div class="pc-divider"></div>
        <div class="pc-stat">
            <div class="pc-stat-val">{{ $user->student_id ?? '—' }}</div>
            <div class="pc-stat-lbl">Student ID</div>
        </div>
    </div>

    <div class="fc">
        <form method="POST" action="{{ route('student.settings.update') }}">
            @csrf @method('PATCH')
            <div class="fst">
                <div class="fst-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                Profile Information
            </div>
            <div class="field"><label>Full Name</label>
                <input type="text" name="name" value="{{ old('name',$user->name) }}" class="{{ $errors->has('name')?'is-invalid':'' }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="field"><label>Email Address</label>
                <input type="email" name="email" value="{{ old('email',$user->email) }}" class="{{ $errors->has('email')?'is-invalid':'' }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="fdiv"></div>
            <div class="fst">
                <div class="fst-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
                Change Password
                <span style="font-size:.68rem;font-weight:400;letter-spacing:0;text-transform:none;color:var(--text-muted);margin-left:4px;">— leave blank to keep</span>
            </div>
            <div class="field"><label>Current Password</label>
                <input type="password" name="current_password" placeholder="••••••••" class="{{ $errors->has('current_password')?'is-invalid':'' }}">
                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="fr">
                <div class="field"><label>New Password</label><input type="password" name="password" placeholder="Min. 8 characters" class="{{ $errors->has('password')?'is-invalid':'' }}">@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="field"><label>Confirm Password</label><input type="password" name="password_confirmation" placeholder="Repeat password"></div>
            </div>
            <div style="margin-top:8px;display:flex;flex-wrap:wrap;gap:8px;">
                <button type="submit" class="bs">Save Changes</button>
                <button type="reset" class="bc">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection