@extends('student.layout')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-sub', 'Manage your account and preferences')

@section('styles')
<style>
    .settings-wrap {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 16px;
        align-items: start;
    }

    .profile-card {
        background: var(--glass);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        backdrop-filter: blur(16px);
        padding: 28px 24px;
        text-align: center;
    }
    .big-avatar {
        width: 72px; height: 72px; border-radius: 50%;
        background: linear-gradient(135deg, var(--accent2), #5147c9);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; font-weight: 800; color: #fff;
        margin: 0 auto 16px;
        border: 3px solid rgba(124,111,247,.3);
        box-shadow: 0 0 24px rgba(124,111,247,.2);
    }
    .profile-name  { font-size: 1rem; font-weight: 700; margin-bottom: 4px; }
    .profile-email { font-size: .78rem; color: var(--text-muted); margin-bottom: 16px; }
    .profile-badge {
        display: inline-block;
        background: rgba(0,229,160,.1);
        border: 1px solid rgba(0,229,160,.2);
        color: var(--accent);
        font-size: .68rem; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase;
        padding: 4px 12px; border-radius: 99px;
    }

    .form-card {
        background: var(--glass);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        backdrop-filter: blur(16px);
        padding: 28px;
    }
    .form-section-title {
        font-size: .72rem; font-weight: 700; letter-spacing: .1em;
        text-transform: uppercase; color: var(--text-muted);
        margin-bottom: 18px; padding-bottom: 12px;
        border-bottom: 1px solid var(--glass-border);
    }
    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .divider { height: 1px; background: var(--glass-border); margin: 24px 0; }

    .btn-save {
        padding: 11px 28px;
        background: var(--accent);
        color: #091510;
        font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .9rem;
        border: none; border-radius: var(--radius-sm);
        cursor: pointer; transition: opacity .18s, transform .18s;
        box-shadow: 0 4px 20px rgba(0,229,160,.25);
    }
    .btn-save:hover { opacity: .88; transform: translateY(-1px); }

    .btn-cancel {
        padding: 11px 20px;
        background: transparent;
        color: var(--text-muted);
        font-family: 'Outfit', sans-serif; font-size: .9rem;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-sm);
        cursor: pointer; margin-left: 10px;
        transition: border-color .18s, color .18s;
    }
    .btn-cancel:hover { border-color: rgba(255,255,255,.2); color: var(--text-soft); }
</style>
@endsection

@section('content')
<div class="settings-wrap">

    {{-- Left: profile snapshot --}}
    <div class="profile-card">
        <div class="big-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="profile-name">{{ $user->name }}</div>
        <div class="profile-email">{{ $user->email }}</div>
        <span class="profile-badge">{{ $user->role }}</span>
    </div>

    {{-- Right: form --}}
    <div class="form-card">
        <form method="POST" action="{{ route('student.settings.update') }}">
            @csrf
            @method('PATCH')

            <div class="form-section-title">Profile Information</div>

            <div class="field">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="divider"></div>
            <div class="form-section-title">
                Change Password
                <span style="font-size:.7rem;font-weight:400;letter-spacing:0;text-transform:none;opacity:.6;"> — leave blank to keep current</span>
            </div>

            <div class="field">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password"
                       placeholder="••••••••"
                       class="{{ $errors->has('current_password') ? 'is-invalid' : '' }}">
                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" placeholder="Min. 8 characters"
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat password">
                </div>
            </div>

            <div style="margin-top:8px;">
                <button type="submit" class="btn-save">Save Changes</button>
                <button type="reset" class="btn-cancel">Cancel</button>
            </div>

        </form>
    </div>

</div>
@endsection