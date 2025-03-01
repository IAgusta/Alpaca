<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModuleContent;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'description'];

    // Relationship with Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    // Relationship with ModuleContent
    public function contents()
    {
        return $this->hasMany(ModuleContent::class, 'module_id', 'id');
    }

    // Automatically delete related contents when a module is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($module) {
            $module->contents()->delete(); // ✅ Delete all contents when a module is deleted
        });
    }
}