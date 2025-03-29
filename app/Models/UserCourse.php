<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserCourse extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'user_course_progress';

    // Fields that can be mass-assigned
    protected $fillable = [
        'user_id',
        'course_id',
        'total_modules',
        'completed_modules',
        'course_completed',
        'course_completed_at',
        'last_opened',
    ];

    // Define relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function getCourseSlugAttribute()
    {
        return Str::slug($this->course->name);
    }

    // Delete course progress
    public function deleteCourse() {
        // Delete all progress for the course
        $this->where('user_id', $this->user_id)
             ->where('course_id', $this->course_id)
             ->delete();
    }
}