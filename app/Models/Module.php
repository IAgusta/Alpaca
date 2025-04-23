<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModuleContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'description', 'author', 'position']; // Add 'author' to fillable fields

    // Relationship with Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function getRouteKeyTitle()
    {
        return Str::slug($this->title);
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

    public function userProgress()
    {
        return $this->hasOne(UserModel::class, 'module_id')
            ->where('user_id', Auth::id());
    }

    public function getIsCompletedAttribute()
    {
        return $this->userProgress?->read ?? false;
    }
}