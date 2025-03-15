<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\UserCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserCourseController extends Controller
{
    public function index() {
        $userId = Auth::id();
        $quickstartCourseId = 1; // Assuming Course 1 has ID 1

        // Check if the quickstart course is already added
        $quickstartCourse = UserCourse::where('user_id', $userId)->where('course_id', $quickstartCourseId)->first();

        // If not, add it to the user's courses
        if (!$quickstartCourse) {
            UserCourse::create([
                'user_id' => $userId,
                'course_id' => $quickstartCourseId,
            ]);
        }

        $userCourses = UserCourse::where('user_id', $userId)->with('course')->get();
        $availableCourses = Course::whereNotIn('id', $userCourses->pluck('course_id'))->get();

        return view('user.course', compact('userCourses', 'availableCourses'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'lock_password' => 'nullable|string',
        ]);
    
        $course = Course::findOrFail($request->course_id);
    
        // Check if the course is locked and if the provided password matches
        if ($course->is_locked && $request->lock_password !== $course->lock_password) {
            return redirect()->route('user.course')->with('error', 'Incorrect password.');
        }
    
        // Add the course to the user's courses
        UserCourse::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
        ]);
    
        return redirect()->route('user.course')->with('success', 'Course added successfully.');
    }

    public function preview($courseId) {
        $course = Course::with('modules.contents')->findOrFail($courseId);
        return view('user.course_preview', compact('course'));
    }

    public function open($courseId) {
        $course = Course::with('modules.contents')->findOrFail($courseId);
        return view('user.course_open', compact('course'));
    }

    public function clearHistory($courseId) {
        $userCourse = UserCourse::where('user_id', Auth::id())->where('course_id', $courseId)->firstOrFail();
        $userCourse->clearHistory();

        return redirect()->route('user.course')->with('success', 'Course history cleared successfully.');
    }

    public function delete($courseId) {
        $userCourse = UserCourse::where('user_id', Auth::id())->where('course_id', $courseId)->firstOrFail();
        $userCourse->deleteCourse();

        return redirect()->route('user.course')->with('success', 'Course deleted successfully.');
    }

}
