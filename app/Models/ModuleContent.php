<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleContent extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'title', 'content_type', 'content', 'position'];

    // Relationship with Module
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}