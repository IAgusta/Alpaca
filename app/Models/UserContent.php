<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserContentProgress extends Model
{
    // Specify the table name
    protected $table = 'user_content_progress';

    // Fields that can be mass-assigned
    protected $fillable = [
        'user_id',
        'module_id',
        'module_content_id',
        'is_correct',
        'selected_answer',
        'submitted_at',
    ];

    // Define relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function content() {
        return $this->belongsTo(ModuleContent::class, 'module_content_id');
    }

}