<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Support\Facades\Cache;
use App\Models\UserCourse;
use Illuminate\Support\Facades\Redis;
use Exception;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = Auth::id();
        
        // Get saved course IDs for authenticated users only
        $userSavedCourses = $user ? UserCourse::where('user_id', $userId)->pluck('course_id') : collect([]);

        try {
            // Try Redis first
            $topCourses = $this->getTopCoursesFromCache();
            $latestCourses = $this->getLatestCoursesFromCache();
        } catch (Exception $e) {
            // Fallback to database cache if Redis fails
            config(['cache.default' => 'database']);
            $topCourses = $this->getTopCoursesFromCache();
            $latestCourses = $this->getLatestCoursesFromCache();
        }

        // Only get user courses if authenticated
        $userCourses = $user ? UserCourse::where('user_id', $userId)
            ->whereHas('course', function ($query) {
                $query->whereRaw("LOWER(name) NOT LIKE '%test%'");
            })
            ->with(['course' => function($query) {
                $query->select('id', 'name', 'image', 'description', 'theme', 'author');
            }])
            ->take(4)
            ->get()
            : collect([]);

        return view('dashboard', compact('user', 'topCourses', 'latestCourses', 'userCourses', 'userSavedCourses'));
    }

    private function getTopCoursesFromCache()
    {
        $cacheKey = 'top_courses';
        
        try {
            return Cache::tags(['courses'])->remember($cacheKey, now()->addHours(12), function () {
                $recentCourses = Course::with(['authorUser', 'modules']) // Eager load relationships
                    ->whereRaw("LOWER(name) NOT LIKE '%test%'")
                    ->where('created_at', '>=', now()->subDays(30))
                    ->select('id', 'name', 'image', 'description', 'theme', 'popularity', 'author', 'created_at')
                    ->orderBy('popularity', 'desc')
                    ->take(10)
                    ->get();

                // If no recent courses, fallback to all courses
                if ($recentCourses->isEmpty()) {
                    return Course::with(['authorUser', 'modules'])
                        ->whereRaw("LOWER(name) NOT LIKE '%test%'")
                        ->select('id', 'name', 'image', 'description', 'theme', 'popularity', 'author', 'created_at')
                        ->orderBy('popularity', 'desc')
                        ->take(10)
                        ->get();
                }

                return $recentCourses;
            });
        } catch (\Exception $e) {
            // Fallback to database if Redis fails
            config(['cache.default' => 'database']);
            return Cache::remember($cacheKey, now()->addHours(12), function () {
                return Course::with(['authorUser', 'modules'])
                    ->whereRaw("LOWER(name) NOT LIKE '%test%'")
                    ->where('created_at', '>=', now()->subDays(30))
                    ->select('id', 'name', 'image', 'description', 'theme', 'popularity', 'author', 'created_at')
                    ->orderBy('popularity', 'desc')
                    ->take(10)
                    ->get();
            });
        }
    }

    private function getLatestCoursesFromCache()
    {
        return Cache::tags(['courses'])->remember('latest_courses', now()->addHours(1), function () {
            return Course::whereRaw("LOWER(name) NOT LIKE '%test%'")
                ->select('id', 'name', 'image', 'description', 'theme', 'author', 'updated_at')
                ->with(['modules' => function($query) {
                    $query->orderBy('updated_at', 'desc')
                        ->select('id', 'course_id', 'title', 'updated_at');
                }])
                ->orderBy('updated_at', 'desc')
                ->take(15)
                ->get();
        });
    }
}