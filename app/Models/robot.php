<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class robot extends Model
{
    protected $table = 'robot';
    protected $fillable = [
        'command',
        'status',
    ];
}
