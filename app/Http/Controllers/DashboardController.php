<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $topCourses = Cache::remember('top_courses', now()->addMinutes(10), function () {
            return Course::where('id', '!=', 1)
                ->orderBy('popularity', 'desc')
                ->take(10)
                ->get();
        });
    
        $latestCourses = Cache::remember('latest_courses', now()->addMinutes(10), function () {
            return Course::where('id', '!=', 1)
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
        });

        return view('dashboard', compact('user','topCourses','latestCourses'));
    }
}