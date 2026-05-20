<?php

namespace App\Http\Controllers;

use App\Models\CampusSpace;
use App\Models\CheckIn;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInController extends \Illuminate\Routing\Controller
{
    // ── Student: Scanner page ─────────────────────────────────────────────────

    public function scannerPage()
    {
        // Active check-in logic removed since middleware prevents reaching here if checked in.
        return view('student.scanner', ['activeCheckIn' => null]);
    }

    // ── Student: Active Check-In Page (Focus Mode) ────────────────────────────

    public function activePage()
    {
        $activeCheckIn = CheckIn::where('user_id', Auth::id())
            ->whereNull('checked_out_at')
            ->with('space')
            ->first();

        if (!$activeCheckIn) {
            return redirect()->route('student.dashboard');
        }

        return view('student.checked-in', compact('activeCheckIn'));
    }

    // ── Student: Handle scanned QR URL ────────────────────────────────────────

    public function handleScan(Request $request)
    {
        $spaceId = $request->query('space');
        $token   = $request->query('token');

        // Validate token
        if (!$spaceId || !$token || !CampusSpace::validateToken((int)$spaceId, $token)) {
            return view('student.checkin-result', [
                'success' => false,
                'message' => 'Invalid or expired QR code. QR codes reset daily at midnight.',
                'space'   => null,
            ]);
        }

        $space = CampusSpace::find($spaceId);
        $user  = Auth::user();

        // Check if already in this space
        $existing = CheckIn::where('user_id', $user->id)
            ->where('campus_space_id', $space->id)
            ->whereNull('checked_out_at')
            ->first();

        if ($existing) {
            return view('student.checkin-result', [
                'success'   => false,
                'message'   => "You're already checked into {$space->building} — {$space->name}.",
                'space'     => $space,
                'checkIn'   => $existing,
                'alreadyIn' => true,
            ]);
        }

        // Check if space is full
        if ($space->current_occupancy >= $space->capacity) {
            return view('student.checkin-result', [
                'success' => false,
                'message' => "{$space->building} — {$space->name} is currently full ({$space->capacity}/{$space->capacity}).",
                'space'   => $space,
                'full'    => true,
            ]);
        }

        // Auto checkout from any previous space
        $prevCheckIn = CheckIn::where('user_id', $user->id)
            ->whereNull('checked_out_at')
            ->with('space')
            ->first();

        if ($prevCheckIn) {
            $prevCheckIn->update(['checked_out_at' => now()]);
            $prevCheckIn->space->decrement('current_occupancy');
        }

        // Create new check-in
        CheckIn::create([
            'user_id'          => $user->id,
            'campus_space_id'  => $space->id,
            'checked_in_at'    => now(),
        ]);

        $space->increment('current_occupancy');

        return redirect()->route('student.checked-in')->with('success', "Successfully checked into {$space->building} — {$space->name}!");
    }

    // ── Student: Manual checkout ──────────────────────────────────────────────

    public function checkout()
    {
        $checkIn = CheckIn::where('user_id', Auth::id())
            ->whereNull('checked_out_at')
            ->with('space')
            ->first();

        if (!$checkIn) {
            return redirect()->route('student.scanner')
                ->with('error', 'You are not currently checked into any space.');
        }

        $checkIn->update(['checked_out_at' => now()]);
        $checkIn->space->decrement('current_occupancy');

        return redirect()->route('student.dashboard')
            ->with('success', "Checked out of {$checkIn->space->building} — {$checkIn->space->name}.");
    }

    // ── Admin: QR codes display page ─────────────────────────────────────────

    public function adminQrPage()
    {
        $spaces = CampusSpace::where('is_active', true)
            ->orderBy('building')
            ->orderBy('name')
            ->get();

        return view('admin.qr-codes', compact('spaces'));
    }
}