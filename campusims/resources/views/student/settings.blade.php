@extends('student.layout')
@section('title','Settings')
@section('page-title','Settings')
@section('page-sub','Manage your account and preferences')

@section('styles')
<style>
    .sw{display:grid;grid-template-columns:240px 1fr;gap:16px;align-items:start;}
    .pc{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:22px;backdrop-filter:blur(16px);padding:28px 24px;text-align:center;box-shadow:inset 0 1px 0 rgba(255,255,255,.07);}
    .big-av{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:800;color:#fff;margin:0 auto 16px;border:3px solid rgba(79,156,249,.35);box-shadow:0 0 24px rgba(79,156,249,.2);}
    .pn{font-size:1rem;font-weight:700;margin-bottom:4px;}
    .pe{font-size:.78rem;color:var(--muted);margin-bottom:16px;word-break:break-all;}
    .pb{display:inline-block;background:rgba(79,156,249,.1);border:1px solid rgba(79,156,249,.2);color:var(--accent2);font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:4px 12px;border-radius:99px;}
    .fc{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:22px;backdrop-filter:blur(16px);padding:28px;box-shadow:inset 0 1px 0 rgba(255,255,255,.07);}
    .fst{font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid rgba(255,255,255,.07);}
    .fr{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .div{height:1px;background:rgba(255,255,255,.07);margin:24px 0;}
    .bs{padding:11px 28px;background:linear-gradient(135deg,#4f9cf9,#1a6fe8);color:#fff;font-family:'Outfit',sans-serif;font-weight:700;font-size:.9rem;border:none;border-radius:10px;cursor:pointer;box-shadow:0 4px 20px rgba(79,156,249,.25);transition:opacity .18s;}
    .bs:hover{opacity:.88;}
    .bc{padding:11px 20px;background:transparent;color:var(--muted);font-family:'Outfit',sans-serif;font-size:.9rem;border:1px solid rgba(255,255,255,.1);border-radius:10px;cursor:pointer;margin-left:10px;}

    @media(max-width:768px){
        .sw{grid-template-columns:1fr;}
        .fr{grid-template-columns:1fr;}
        .fc{padding:20px;}
        .pc{padding:20px;}
        .bs{width:100%;}
        .bc{width:100%;margin-left:0;margin-top:10px;}
    }
</style>
@endsection

@section('content')
<div class="sw">
    <div class="pc">
        <div class="big-av">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="pn">{{ $user->name }}</div>
        <div class="pe">{{ $user->email }}</div>
        <span class="pb">{{ $user->role }}</span>
    </div>
    <div class="fc">
        <form method="POST" action="{{ route('student.settings.update') }}">
            @csrf @method('PATCH')
            <div class="fst">Profile Information</div>
            <div class="field"><label>Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="field"><label>Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="div"></div>
            <div class="fst">Change Password <span style="font-size:.7rem;font-weight:400;letter-spacing:0;text-transform:none;opacity:.5;">— leave blank to keep</span></div>
            <div class="field"><label>Current Password</label>
                <input type="password" name="current_password" placeholder="••••••••" class="{{ $errors->has('current_password') ? 'is-invalid' : '' }}">
                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="fr">
                <div class="field"><label>New Password</label><input type="password" name="password" placeholder="Min. 8 characters">@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="field"><label>Confirm Password</label><input type="password" name="password_confirmation" placeholder="Repeat password"></div>
            </div>
            <div style="margin-top:8px;display:flex;flex-wrap:wrap;gap:10px;">
                <button type="submit" class="bs">Save Changes</button>
                <button type="reset" class="bc">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection