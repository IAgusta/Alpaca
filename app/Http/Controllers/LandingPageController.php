<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Cache;
use Exception;

class LandingPageController extends Controller
{
    public function index()
    {
        try {
            // Try Redis first with cache tags
            $favoriteCourses = Cache::tags(['courses'])->remember('favorite_courses', now()->addHours(6), function () {
                return Course::where('id', '!=', 1)
                    ->whereRaw("LOWER(name) NOT LIKE '%test%'")
                    ->select('id', 'name', 'image', 'description', 'theme', 'popularity', 'author', 'created_at', 'updated_at')
                    ->with(['authorUser' => function($query) {
                        $query->select('id', 'name');
                    }])
                    ->orderBy('popularity', 'desc')
                    ->take(3)
                    ->get()
                    ->map(function ($course) {
                        // Ensure updated_at is never null
                        $course->updated_at = $course->updated_at ?? $course->created_at ?? now();
                        return $course;
                    });
            });
        } catch (Exception $e) {
            // Fallback to database cache if Redis fails
            config(['cache.default' => 'database']);
            $favoriteCourses = Cache::remember('favorite_courses', now()->addHours(6), function () {
                return Course::where('id', '!=', 1)
                    ->whereRaw("LOWER(name) NOT LIKE '%test%'")
                    ->select('id', 'name', 'image', 'description', 'theme', 'popularity', 'author', 'created_at', 'updated_at')
                    ->with(['authorUser' => function($query) {
                        $query->select('id', 'name');
                    }])
                    ->orderBy('popularity', 'desc')
                    ->take(3)
                    ->get()
                    ->map(function ($course) {
                        // Ensure updated_at is never null
                        $course->updated_at = $course->updated_at ?? $course->created_at ?? now();
                        return $course;
                    });
            });
        }
        
        return view('about', compact('favoriteCourses'));
    }
}
