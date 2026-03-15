<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Controller extends \Illuminate\Routing\Controller
{
    // ── Login ─────────────────────────────────────────────────────────────────

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Unknown email
        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        // Account locked
        if ($user->isLocked()) {
            $minutes = now()->diffInMinutes($user->locked_until, false);
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => "Account temporarily locked due to too many failed attempts. Try again in {$minutes} minute(s)."]);
        }

        // Wrong password — track attempt
        if (!Hash::check($request->password, $user->password)) {
            $user->incrementLoginAttempts();
            $remaining = max(0, 5 - $user->login_attempts);
            $msg = $user->isLocked()
                ? 'Too many failed attempts. Account locked for 15 minutes.'
                : "Incorrect password. {$remaining} attempt(s) remaining before lockout.";
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => $msg]);
        }

        // Pending
        if ($user->isPending()) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Your account is pending admin approval. Please wait.']);
        }

        // Rejected
        if ($user->isRejected()) {
            $reason = $user->rejection_reason
                ? "Your registration was rejected. Reason: {$user->rejection_reason}. Please contact the admin."
                : 'Your registration was rejected. Please contact the admin.';
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => $reason]);
        }

        // Deactivated
        if (!$user->is_active) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Your account has been deactivated. Please contact the admin.']);
        }

        // Success
        $user->clearLoginAttempts();
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.dashboard');
    }

    // ── Register ──────────────────────────────────────────────────────────────

    public function showRegister()
    {
        return view('registration');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'student_id' => ['required', 'string', 'regex:/^\d{2}-\d{4}-\d{3}$/', 'unique:users,student_id'],
            'id_image'   => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'student_id.regex'  => 'Student ID must follow the format: ##-####-### (e.g. 23-0066-858)',
            'student_id.unique' => 'This Student ID is already registered.',
            'id_image.required' => 'Please upload a photo of your student ID.',
            'id_image.max'      => 'Image must be under 10MB.',
        ]);

        $imageName = time() . '_' . $request->file('id_image')->getClientOriginalName();
        $request->file('id_image')->storeAs('id_images', $imageName, 'public');

        User::create([
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 'student',
            'student_id' => $data['student_id'],
            'id_image'   => $imageName,
            'status'     => 'pending',
            'is_active'  => true,
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration submitted! Please wait for admin approval before logging in.');
    }

    // ── Forgot Password ───────────────────────────────────────────────────────

    public function showForgotPassword()
    {
        return view('forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->first();

        // Always show success to prevent email enumeration
        if (!$user) {
            return back()->with('success', 'If that email exists, a reset link has been generated.');
        }

        // Generate token
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

        // Since no email service — show the link directly on screen
        return back()->with('reset_link', $resetUrl);
    }

    public function showResetPassword(Request $request)
    {
        return view('reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => ['required', 'email'],
            'token'                 => ['required'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Invalid or expired reset link.']);
        }

        // Expire after 60 minutes
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This reset link has expired. Please request a new one.']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Password reset successfully. You can now log in.');
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}