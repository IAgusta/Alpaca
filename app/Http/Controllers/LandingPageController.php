<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class LandingPageController extends Controller
{
    public function index()
    {
        // Get the top 3 courses based on popularity, excluding course with ID 1
        $topCourses = Course::where('id', '!=', 1)->orderBy('popularity', 'desc')->take(3)->get();

        return view('welcome', compact('topCourses'));
    }
}
