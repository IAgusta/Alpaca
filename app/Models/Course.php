<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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

    /**
     * Get the display name (without (test), (comics), (novel), or [Alternative])
     */
    public function getDisplayNameAttribute()
    {
        // Remove (test), (comics), (novel), and [anything] from the name
        return trim(preg_replace('/\s*(\((test|comics|novel)\))?\s*(\[[^\]]*\])?$/i', '', $this->name));
    }

    public function getSlugAttribute()
    {
        // Slugify the display name
        return Str::slug($this->display_name);
    }


    /**
     * Get the title type (test, comics, or novel) without parentheses
     */
    public function getTitleTypeAttribute()
    {
        if (preg_match('/\((test|comics|novel)\)/i', $this->name, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Get the alternative name (without brackets)
     */
    public function getAltNameAttribute()
    {
        if (preg_match('/\[(.*?)\]$/', $this->name, $matches)) {
            return $matches[1];
        }
        return null;
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
