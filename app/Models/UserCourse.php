<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    ];

    // Define relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    // Clear progress for a course
    public function clearHistory() {
        // Clear content progress
        DB::table('user_content_progress')
            ->where('user_id', $this->user_id)
            ->whereIn('module_content_id', function ($query) {
                $query->select('id')
                      ->from('module_contents')
                      ->whereIn('module_id', function ($query) {
                          $query->select('id')
                                ->from('modules')
                                ->where('course_id', $this->course_id);
                      });
            })
            ->delete();

        // Clear course progress
        $this->where('user_id', $this->user_id)
             ->where('course_id', $this->course_id)
             ->delete();
    }

    // Delete course progress
    public function deleteCourse() {
        // Delete all progress for the course
        $this->where('user_id', $this->user_id)
             ->where('course_id', $this->course_id)
             ->delete();
    }

    // Helper method to get course-level progress
    public static function getCourseProgress($userId, $courseId) {
        return self::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    }

    // Helper method to mark a module as completed
    public static function markModuleAsCompleted($userId, $courseId) {
        // Increment the completed_modules count
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'course_id' => $courseId,
            ],
            [
                'completed_modules' => DB::raw('completed_modules + 1'),
            ]
        );
    }

    // Helper method to mark a course as completed
    public static function markCourseAsCompleted($userId, $courseId) {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'course_id' => $courseId,
            ],
            [
                'course_completed' => true,
                'course_completed_at' => now(),
            ]
        );
    }

    // Helper method to check if a course is completed
    public static function isCourseCompleted($userId, $courseId) {
        return self::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('course_completed', true)
            ->exists();
    }
}