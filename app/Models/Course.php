<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Module;
use App\Models\User;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'author', 'theme', 'is_locked', 'lock_password'];

    // Relationship with Modules
    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id', 'id');
    }

    // Relationship with User (Author)
    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }

    // Automatically delete related modules when a course is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($course) {
            $course->modules()->delete();
        });
    }
}
