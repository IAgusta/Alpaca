<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Module;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'author'];

    // Relationship with Modules
    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id', 'id');
    }

    // Automatically delete related modules when a course is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($course) {
            $course->modules()->delete(); // ✅ Delete all modules when a course is deleted
        });
    }
}