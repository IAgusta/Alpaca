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
        $topCourses = Cache::remember('top_courses', now()->addHours(12), function () {
            return Course::where('id', '!=', 1)
                ->whereRaw("LOWER(name) NOT LIKE '%test%'")
                ->orderBy('popularity', 'desc')
                ->take(10)
                ->get();
        });
    
        $latestCourses = Cache::remember('latest_courses', now()->addMinutes(3), function () {
            return Course::where('id', '!=', 1)
                ->whereRaw("LOWER(name) NOT LIKE '%test%'")
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
        });

        $userCourses = UserCourse::where('user_id', $userId)
            ->whereHas('course', function ($query) {
                $query->where('id', '!=', 1);
            })->with('course')->take(3)->get();

        return view('dashboard', compact('user','topCourses','latestCourses', 'userCourses'));
    }
}