<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class robotDetail extends Model
{
    protected $table = 'robot_detail';
    
    protected $fillable = [
        'robot_id',
        'robot_image',
        'controller',
        'components',
        'isPublic',
    ];

    protected $casts = [
        'components' => 'array',
        'isPublic' => 'boolean',
    ];

    public function robot()
    {
        return $this->belongsTo(robot::class, 'robot_id');
    }
}
