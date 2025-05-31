<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Support\Facades\Cache;
use App\Models\UserCourse;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = Auth::id();
        
        // Get saved course IDs for authenticated users only
        $userSavedCourses = $user ? UserCourse::where('user_id', $userId)->pluck('course_id') : collect([]);

        $topCourses = Cache::remember('top_courses', now()->addHours(12), function () {
            $recentCourses = Course::whereRaw("LOWER(name) NOT LIKE '%test%'")
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('popularity', 'desc')
            ->take(10)
            ->get();

            // If no recent courses, fallback to all courses
            if ($recentCourses->isEmpty()) {
            return Course::whereRaw("LOWER(name) NOT LIKE '%test%'")
                ->orderBy('popularity', 'desc')
                ->take(10)
                ->get();
            }

            return $recentCourses;
        });
        
        $latestCourses = Cache::remember('latest_courses', now()->addMinutes(1), function () {
            return Course::whereRaw("LOWER(name) NOT LIKE '%test%'")
                ->with(['modules' => function($query) {
                    $query->orderBy('updated_at', 'desc');
                }])
                ->orderBy('updated_at', 'desc')
                ->take(15)
                ->get();
        });

        // Only get user courses if authenticated
        $userCourses = $user ? UserCourse::where('user_id', $userId)
            ->whereHas('course', function ($query) {
                $query->whereRaw("LOWER(name) NOT LIKE '%test%'");
            })->with('course')->take(4)->get()
            : collect([]);

        return view('dashboard', compact('user', 'topCourses', 'latestCourses', 'userCourses', 'userSavedCourses'));
    }
}