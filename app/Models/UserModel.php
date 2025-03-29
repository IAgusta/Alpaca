<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    // Specify the table name
    protected $table = 'user_module_progress';

    // Fields that can be mass-assigned
    protected $fillable = [
        'user_id',
        'module_id',
        'read',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function module() {
        return $this->belongsTo(Module::class);
    }

}
