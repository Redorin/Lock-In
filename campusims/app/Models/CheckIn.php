<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $fillable = [
        'user_id',
        'campus_space_id',
        'checked_in_at',
        'checked_out_at',
        'auto_checked_out',
    ];

    protected $casts = [
        'checked_in_at'    => 'datetime',
        'checked_out_at'   => 'datetime',
        'auto_checked_out' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function space()
    {
        return $this->belongsTo(CampusSpace::class, 'campus_space_id');
    }

    public function isActive(): bool
    {
        return is_null($this->checked_out_at);
    }
}