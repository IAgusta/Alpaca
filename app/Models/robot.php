<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class robot extends Model
{
    protected $table = 'robot';
    protected $fillable = [
        'command',
        'status',
        'api_key',
        'api_key_last_reset',
        'user_id'
    ];

    protected $casts = [
        'api_key_last_reset' => 'datetime',
        'status' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function findByUser($userId)
    {
        return static::where('user_id', $userId)->first();
    }

    public function generateApiKey()
    {
        $this->api_key = Str::random(32);
        $this->api_key_last_reset = now();
        $this->save();
        
        return $this->api_key;
    }

    public function canResetApiKey()
    {
        if (!$this->api_key_last_reset) return true;
        return $this->api_key_last_reset->addWeek()->isPast();
    }
}
