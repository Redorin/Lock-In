<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusSpace extends Model
{
    protected $fillable = [
        'name', 'building', 'capacity',
        'current_occupancy', 'image', 'is_active',
    ];

    public function getOccupancyPercentAttribute(): int
    {
        if ($this->capacity === 0) return 0;
        return (int) round(($this->current_occupancy / $this->capacity) * 100);
    }

    public function getStatusAttribute(): string
    {
        $pct = $this->occupancy_percent;
        if ($pct >= 100) return 'FULL';
        if ($pct >= 75)  return 'HIGH';
        if ($pct >= 40)  return 'MODERATE';
        return 'LOW';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'FULL'     => 'status-full',
            'HIGH'     => 'status-high',
            'MODERATE' => 'status-moderate',
            default    => 'status-low',
        };
    }

    // ── Daily rotating QR token ───────────────────────────────────────────────

    public function dailyToken(): string
    {
        $payload = $this->id . '|' . now()->format('Y-m-d');
        return hash_hmac('sha256', $payload, config('app.key'));
    }

    public static function validateToken(int $spaceId, string $token): bool
    {
        $space = static::find($spaceId);
        if (!$space) return false;
        return hash_equals($space->dailyToken(), $token);
    }

    public function checkinUrl(): string
    {
        return route('checkin.scan', [
            'space' => $this->id,
            'token' => $this->dailyToken(),
        ]);
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }

    public function activeCheckIns()
    {
        return $this->hasMany(CheckIn::class)->whereNull('checked_out_at');
    }
}