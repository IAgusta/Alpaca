<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'last_seen',
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

    // The atributes that should will protect for softdeletion.
    protected $dates = ['deleted_at'];

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
            'last_seen' => 'datetime',
        ];
    }

    /**
     * Relationship with UserDetail.
     */
    public function details()
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    /**
     * Get the username attribute.
     *
     * @return string
     */
    public function getUsernameAttribute()
    {
        return $this->attributes['username'];
    }

    /**
     * Scope a query to only include users who have not verified their email
     * and were created more than 30 days ago.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnverifiedOld($query)
    {
        return $query->whereNull('email_verified_at')
                    ->where('created_at', '<', now()->subDays(30));
    }

    /**
     * Check if the user is online.
     *
     * @return bool
     */
    public function isOnline()
    {
        // Get the most recent session's activity for this user
        $lastActivity = DB::table('sessions')
            ->where('user_id', $this->id)
            ->latest('last_activity')
            ->value('last_activity');
    
        // If no activity found, consider the user offline
        if (!$lastActivity) return false;
    
        // Convert the timestamp to a Carbon instance
        $lastActivityTime = Carbon::createFromTimestamp($lastActivity);
    
        // Determine if the user is online (active in the last 30 minutes)
        return $lastActivityTime->diffInMinutes(now()) <= 30;
    }
}