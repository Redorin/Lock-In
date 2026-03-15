<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusSpace extends Model
{
    protected $fillable = [
        'name',
        'building',
        'capacity',
        'current_occupancy',
        'image',
        'is_active',
    ];

    /**
     * Occupancy percentage (0–100)
     */
    public function getOccupancyPercentAttribute(): int
    {
        if ($this->capacity === 0) return 0;
        return (int) round(($this->current_occupancy / $this->capacity) * 100);
    }

    /**
     * Status label: LOW / MODERATE / HIGH / FULL
     */
    public function getStatusAttribute(): string
    {
        $pct = $this->occupancy_percent;
        if ($pct >= 100) return 'FULL';
        if ($pct >= 75)  return 'HIGH';
        if ($pct >= 40)  return 'MODERATE';
        return 'LOW';
    }

    /**
     * CSS color class for the status badge
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'FULL'     => 'status-full',
            'HIGH'     => 'status-high',
            'MODERATE' => 'status-moderate',
            default    => 'status-low',
        };
    }
}