<?php

namespace App\Console\Commands;

use App\Models\CheckIn;
use Illuminate\Console\Command;

class AutoCheckout extends Command
{
    protected $signature   = 'checkins:auto-checkout';
    protected $description = 'Auto checkout students who have been checked in for more than 2 hours';

    public function handle(): void
    {
        $cutoff = now()->subHours(2);

        $stale = CheckIn::whereNull('checked_out_at')
            ->where('checked_in_at', '<=', $cutoff)
            ->with('space')
            ->get();

        foreach ($stale as $checkIn) {
            $checkIn->update([
                'checked_out_at'   => now(),
                'auto_checked_out' => true,
            ]);

            if ($checkIn->space && $checkIn->space->current_occupancy > 0) {
                $checkIn->space->decrement('current_occupancy');
            }
        }

        $this->info("Auto-checked out {$stale->count()} student(s).");
    }
}