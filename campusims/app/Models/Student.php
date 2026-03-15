<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_number',
        'first_name',
        'last_name',
        'middle_name',
        'birth_date',
        'gender',
        'contact_number',
        'address',
        'course',
        'year_level',
        'section',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    /** The user account linked to this student */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    /** Full name helper: "Juan dela Cruz" */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }
}