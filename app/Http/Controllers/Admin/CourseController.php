<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }
    
public function store(Request $request)
{

    $validated = $request->validate([
        'name' => 'required|string|max:255', // Ensure 'name' is required
        'description' => 'nullable|string',
        'image' => 'nullable|image',
    ]);

    $imagePath = $request->file('image') ? $request->file('image')->store('courses', 'public') : null;

    Course::create([
        'name' => $request->input('name'), // ✅ Make sure 'name' is included
        'description' => $request->input('description'),
        'image' => $imagePath,
        'author' => Auth::user()->name, // ✅ Ensure user is authenticated
    ]);

    return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
}

    

    public function destroy(Course $course)
    {
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
