<?php

namespace App\Http\Controllers;

use App\Models\CampusSpace;
use App\Models\CheckIn;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            ActivityLog::log('failed Check-In', 'Invalid or expired QR token scanned.', 'QR scan');

            return view('student.checkin-result', [
                'success' => false,
                'message' => 'Invalid or expired QR code. QR codes refresh every 15 minutes.',
                'space'   => null,
            ]);
        }

        $user  = Auth::user();

        $result = DB::transaction(function () use ($spaceId, $user) {
            $space = CampusSpace::whereKey($spaceId)->lockForUpdate()->first();

            if (!$space) {
                return [
                    'type' => 'error',
                    'view' => [
                        'success' => false,
                        'message' => 'Invalid or expired QR code. QR codes refresh every 15 minutes.',
                        'space'   => null,
                    ],
                ];
            }

            $existing = CheckIn::where('user_id', $user->id)
                ->where('campus_space_id', $space->id)
                ->whereNull('checked_out_at')
                ->lockForUpdate()
                ->first();

            if ($existing) {
                ActivityLog::log('duplicate Check-In', "Already checked into {$space->building} - {$space->name}.", $space->name);

                return [
                    'type' => 'error',
                    'view' => [
                        'success'   => false,
                        'message'   => "You're already checked into {$space->building} — {$space->name}.",
                        'space'     => $space,
                        'checkIn'   => $existing,
                        'alreadyIn' => true,
                    ],
                ];
            }

            if ($space->current_occupancy >= $space->capacity) {
                ActivityLog::log('failed Check-In', "Attempted check-in to full space: {$space->building} - {$space->name}.", $space->name);

                return [
                    'type' => 'error',
                    'view' => [
                        'success' => false,
                        'message' => "{$space->building} — {$space->name} is currently full ({$space->capacity}/{$space->capacity}).",
                        'space'   => $space,
                        'full'    => true,
                    ],
                ];
            }

            $prevCheckIn = CheckIn::where('user_id', $user->id)
                ->whereNull('checked_out_at')
                ->with('space')
                ->lockForUpdate()
                ->first();

            if ($prevCheckIn) {
                $prevCheckIn->update([
                    'checked_out_at' => now(),
                    'active_user_id' => null,
                ]);
                $this->decrementOccupancy($prevCheckIn->space);
            }

            CheckIn::create([
                'user_id'         => $user->id,
                'active_user_id'  => $user->id,
                'campus_space_id' => $space->id,
                'checked_in_at'   => now(),
            ]);

            $space->increment('current_occupancy');
            ActivityLog::log('checked In', "Checked into {$space->building} - {$space->name}.", $space->name);

            return [
                'type' => 'success',
                'message' => "Successfully checked into {$space->building} — {$space->name}!",
            ];
        });

        if ($result['type'] === 'error') {
            return view('student.checkin-result', $result['view']);
        }

        return redirect()->route('student.checked-in')->with('success', $result['message']);
    }

    // ── Student: Manual checkout ──────────────────────────────────────────────

    public function checkout()
    {
        $space = DB::transaction(function () {
            $checkIn = CheckIn::where('user_id', Auth::id())
                ->whereNull('checked_out_at')
                ->with('space')
                ->lockForUpdate()
                ->first();

            if (! $checkIn) {
                return null;
            }

            $checkIn->update([
                'checked_out_at' => now(),
                'active_user_id' => null,
            ]);
            $this->decrementOccupancy($checkIn->space);
            ActivityLog::log('checked Out', "Checked out of {$checkIn->space->building} - {$checkIn->space->name}.", $checkIn->space->name);

            return $checkIn->space;
        });

        if (!$space) {
            return redirect()->route('student.scanner')
                ->with('error', 'You are not currently checked into any space.');
        }

        return redirect()->route('student.dashboard')
            ->with('success', "Checked out of {$space->building} — {$space->name}.");
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

    private function decrementOccupancy(?CampusSpace $space): void
    {
        if (!$space) {
            return;
        }

        CampusSpace::whereKey($space->id)
            ->where('current_occupancy', '>', 0)
            ->decrement('current_occupancy');
    }
}
