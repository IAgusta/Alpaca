<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'completed_modules',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

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

        // Clear module progress
        DB::table('user_module_progress')
            ->where('user_id', $this->user_id)
            ->whereIn('module_id', function ($query) {
                $query->select('id')
                      ->from('modules')
                      ->where('course_id', $this->course_id);
            })
            ->delete();

        // Reset completed modules count
        $this->update(['completed_modules' => 0]);
    }

    public function deleteCourse() {
        // Delete user course
        $this->delete();
    }
}
