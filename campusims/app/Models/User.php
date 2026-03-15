<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'student_id', 'id_image', 'status',
        'rejection_reason', 'is_active',
        'login_attempts', 'locked_until',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
        'locked_until'      => 'datetime',
    ];

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isStudent(): bool  { return $this->role === 'student'; }
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function incrementLoginAttempts(): void
    {
        $this->login_attempts += 1;
        if ($this->login_attempts >= 5) {
            $this->locked_until   = Carbon::now()->addMinutes(15);
            $this->login_attempts = 0;
        }
        $this->save();
    }

    public function clearLoginAttempts(): void
    {
        $this->login_attempts = 0;
        $this->locked_until   = null;
        $this->save();
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
}