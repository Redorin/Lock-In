<?php

namespace App\Console\Commands;

use App\Models\CampusSpace;
use App\Models\CheckIn;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RepairOccupancy extends Command
{
    protected $signature = 'spaces:repair-occupancy {--dry-run : Show changes without updating spaces}';
    protected $description = 'Recalculate current occupancy from active check-ins';

    public function handle(): int
    {
        $activeCounts = CheckIn::whereNull('checked_out_at')
            ->select('campus_space_id', DB::raw('count(*) as total'))
            ->groupBy('campus_space_id')
            ->pluck('total', 'campus_space_id');

        $spaces = CampusSpace::orderBy('building')->orderBy('name')->get();
        $changed = 0;

        DB::transaction(function () use ($spaces, $activeCounts, &$changed) {
            foreach ($spaces as $space) {
                $actual = (int) ($activeCounts[$space->id] ?? 0);

                if ((int) $space->current_occupancy === $actual) {
                    continue;
                }

                $changed++;
                $this->line("{$space->building} - {$space->name}: {$space->current_occupancy} -> {$actual}");

                if (!$this->option('dry-run')) {
                    $space->update(['current_occupancy' => $actual]);
                }
            }
        });

        if ($changed === 0) {
            $this->info('All space occupancy counts are already correct.');
            return self::SUCCESS;
        }

        $this->info($this->option('dry-run')
            ? "{$changed} space(s) would be repaired."
            : "{$changed} space(s) repaired.");

        return self::SUCCESS;
    }
}
