<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'birth_date',
        'phone',
        'password',
        'role',
        'active',
        'last_role_change',
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
            'last_role_change' => 'datetime',
        ];
    }

    /**
     * Check if the user can change their role (only after 24 hours).
     */
    public function canChangeRole()
    {
        // If last_role_change is null, allow the change
        if ($this->last_role_change === null) {
            return true;
        }
    
        // Check if 24 hours have passed since the last role change
        $lastChange = Carbon::parse($this->last_role_change);
        return $lastChange->diffInHours(Carbon::now()) >= 24;
    }
}
