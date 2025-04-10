<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image',
        'author',
        'theme',
        'is_locked',
        'lock_password',
        'popularity',
    ];

    // Relationship with Modules
    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id', 'id');
    }

    public function userProgress() {
        return $this->hasMany(UserCourse::class, 'course_id');
    }

    // Relationship with User (Author)
    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }

    // Automatically soft delete related modules when a course is soft deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($course) {
            if ($course->isForceDeleting()) {
                // If it's a permanent delete, also permanently delete related modules
                $course->modules()->forceDelete();
            } else {
                // Otherwise, just soft delete related modules
                $course->modules()->delete();
            }
        });

        static::restoring(function ($course) {
            // Restore soft deleted modules when restoring a course
            $course->modules()->restore();
        });
    }
}
