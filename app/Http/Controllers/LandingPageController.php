<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    public function index()
    {
        // Get the top 3 courses based on popularity, excluding course with ID 1
        $favoriteCourses = Cache::remember('favorite_courses', now()->addMinutes(10), function () {
            return Course::where('id', '!=', 1)
                ->whereRaw("LOWER(name) NOT LIKE '%test%'")
                ->orderBy('popularity', 'desc')
                ->take(3)
                ->get();
        });
        
        return view('welcome', compact('favoriteCourses'));
    }
}
