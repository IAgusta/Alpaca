<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'trainer') {
            // Show only courses created by the logged-in trainer
            $courses = Course::where('author', $user->id)->get();
        } else {
            // Admin sees all courses
            $courses = Course::all();
        }

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'theme' => 'nullable|string|max:255',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('courses', 'public') : null;

        Course::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image' => $imagePath,
            'author' => Auth::id(),
            'theme' => $request->input('theme'),
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        
        // Allow only admins or course owners to edit
        if (Auth::user()->role !== 'admin' && intval($course->author) !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Retrieve the plain text password from the session if it exists
        $plainPassword = session('current_lock_password_' . $course->id, null);
    
        return view('admin.courses.edit', compact('course', 'plainPassword'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
    
        // Allow only admins or course owners to update
        if (Auth::user()->role !== 'admin' && intval($course->author) !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'theme' => 'nullable|string|max:255',
        ]);
    
        $course->name = $request->name;
        $course->description = $request->description;
        $course->theme = $request->theme;
    
        if ($request->hasFile('image')) {
            if ($course->image) {
                Storage::delete('public/' . $course->image);
            }
    
            $imagePath = $request->file('image')->store('courses', 'public');
            $course->image = $imagePath;
        }
    
        $course->save();
    
        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Request $request, Course $course)
    {
        // Allow only admins or course owners to delete
        if (Auth::user()->role !== 'admin' && intval($course->author) !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    
        // Validate the course name
        $request->validate([
            'course_name' => ['required', 'string', 'in:' . $course->name],
        ]);
    
        // Delete the course image if it exists
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }
    
        // Delete the course
        $course->delete();
    
        // Redirect with a success message
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }

    public function lockCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // Allow only course owners to lock
        if (intval($course->author) !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $password = $request->lock_password ?: Str::random(8);

        $course->update([
            'is_locked' => true,
            'lock_password' => $password, // Store plain password
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Course locked successfully. Password: ' . $password);
    }

    public function unlockCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // Allow only course owners to unlock
        if (intval($course->author) !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($request->lock_password === $course->lock_password) {
            $course->update([
                'is_locked' => false,
                'lock_password' => null,
            ]);

            return redirect()->route('admin.courses.index')->with('success', 'Course unlocked successfully.');
        } else {
            return redirect()->route('admin.courses.index')->with('error', 'Incorrect password.');
        }
    }
}
