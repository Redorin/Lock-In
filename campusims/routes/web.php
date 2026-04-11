<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckInController;

// ── Root ──────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});

// ── Guest ─────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',           [Controller::class, 'showLogin'])->name('login');
    Route::post('/login',          [Controller::class, 'login'])->name('login.post');
    Route::get('/register',        [Controller::class, 'showRegister'])->name('register');
    Route::post('/register',       [Controller::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [Controller::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password',[Controller::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password',  [Controller::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [Controller::class, 'resetPassword'])->name('password.update');
});

// ── Logout ────────────────────────────────────────────────────────────────────
Route::post('/logout', [Controller::class, 'logout'])->name('logout')->middleware('auth');

// ── Student ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', \App\Http\Middleware\RedirectIfRejected::class])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/map',       [StudentDashboardController::class, 'map'])->name('map');
    Route::get('/id-card',   [StudentDashboardController::class, 'idCard'])->name('id-card');
    Route::get('/settings',  [StudentDashboardController::class, 'settings'])->name('settings');
    Route::patch('/settings',[StudentDashboardController::class, 'updateSettings'])->name('settings.update');
    Route::get('/scanner',   [CheckInController::class, 'scannerPage'])->name('scanner');
});

// ── Resubmit Portal (For Rejected Students) ───────────────────────────────────
Route::middleware(['auth', \App\Http\Middleware\EnsureRejected::class])->prefix('student')->name('student.')->group(function () {
    Route::get('/resubmit',  [StudentDashboardController::class, 'resubmitPage'])->name('resubmit');
    Route::post('/resubmit', [StudentDashboardController::class, 'processResubmit'])->name('resubmit.post');
});

// ── Check-in (auth required) ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // QR scan lands here — validates token and checks in
    Route::get('/checkin/scan',    [CheckInController::class, 'handleScan'])->name('checkin.scan');
    // Manual checkout
    Route::post('/checkin/checkout',[CheckInController::class, 'checkout'])->name('checkin.checkout');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',  [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/spaces',            [AdminController::class, 'spaces'])->name('spaces');
    Route::post('/spaces',           [AdminController::class, 'storeSpace'])->name('spaces.store');
    Route::patch('/spaces/{space}',  [AdminController::class, 'updateSpace'])->name('spaces.update');
    Route::delete('/spaces/{space}', [AdminController::class, 'destroySpace'])->name('spaces.destroy');

    Route::get('/users',                  [AdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/toggle',  [AdminController::class, 'toggleActive'])->name('users.toggle');
    Route::delete('/users/{user}',        [AdminController::class, 'destroyUser'])->name('users.destroy');

    Route::get('/verifications',                  [AdminController::class, 'verifications'])->name('verifications');
    Route::patch('/verifications/{user}/approve', [AdminController::class, 'approveUser'])->name('verifications.approve');
    Route::patch('/verifications/{user}/reject',  [AdminController::class, 'rejectUser'])->name('verifications.reject');

    Route::get('/admins',           [AdminController::class, 'admins'])->name('admins');
    Route::post('/admins',          [AdminController::class, 'storeAdmin'])->name('admins.store');
    Route::delete('/admins/{user}', [AdminController::class, 'destroyAdmin'])->name('admins.destroy');

    Route::get('/qr-codes',      [CheckInController::class, 'adminQrPage'])->name('qr-codes');
    Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');
});