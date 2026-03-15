<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'subject_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to quickly log an action
     */
    public static function log(string $action, string $description, string $subjectName = null): void
    {
        static::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'description'  => $description,
            'subject_name' => $subjectName,
        ]);
    }
}