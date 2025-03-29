<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Module;
use App\Models\ModuleContent;
use App\Models\UserCourse;
use App\Models\UserContentProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserCourseController extends Controller
{
    public function index() 
    {
        $userId = Auth::id();
        $quickstartCourseId = 1; // Assuming Course 1 has ID 1

        // Check if the quickstart course is already added
        $quickstartCourse = UserCourse::where('user_id', $userId)
            ->where('course_id', $quickstartCourseId)
            ->first();

        // If not, add it to the user's courses
        if (!$quickstartCourse) {
            UserCourse::create([
                'user_id' => $userId,
                'course_id' => $quickstartCourseId,
                'total_modules' => Course::find($quickstartCourseId)->modules->count(),
                'completed_modules' => 0,
                'course_completed' => false,
            ]);
        }

        // Get all user courses
        $userCourses = UserCourse::where('user_id', $userId)
            ->with('course')
            ->get();

        // Get available courses that the user hasn't added yet
        $availableCourses = Course::whereNotIn('id', $userCourses->pluck('course_id'))->get();

        return view('user.course', compact('userCourses', 'availableCourses'));
    }

    public function detail($name, $courseId) 
    {
        $userId = Auth::id();
    
        // Optional: Verify the name matches the course (for SEO)
        $course = Course::with(['modules.contents'])->findOrFail($courseId);
        
        // If you want to ensure URL consistency (recommended)
        $expectedSlug = Str::slug($course->name);
        if ($expectedSlug !== $name) {
            // Redirect to correct URL if name doesn't match
            return redirect()->route('user.course.detail', [
                'name' => $expectedSlug,
                'courseId' => $courseId
            ]);
        }
    
        // Rest of your existing logic...
        $userHasCourse = UserCourse::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    
        if ($userHasCourse) {
            UserCourse::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->update(['last_opened' => now()]);
        }
    
        $userCourses = UserCourse::where('user_id', $userId)
            ->with('course')
            ->get();
    
        $savedCourses = UserCourse::where('course_id', $courseId)->count();
    
        return view('user.course_detail', compact('course', 'userCourses', 'savedCourses'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'lock_password' => 'nullable|string',
        ]);

        $course = Course::findOrFail($request->course_id);

        // Check if the course is locked
        if ($course->is_locked) {
            try {
                $decryptedPassword = Crypt::decryptString($course->lock_password);
            } catch (\Exception $e) {
                return back()->with('error', 'Password decryption failed.');
            }
            if ($request->lock_password !== $decryptedPassword) {
                return back()->with('error', 'Incorrect password.');
            }
        }

        // Add the course to the user's courses
        UserCourse::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'total_modules' => $course->modules->count(),
            'completed_modules' => 0,
            'course_completed' => false,
        ]);

        $course->timestamps = false; // Disable timestamp updates
        $course->updateQuietly(['popularity' => $course->popularity + 1]); // Update quietly

        return back()->with('success', 'Course added to Bookmark.');
    }

    public function open($name, $courseId, $moduleTitle, $moduleId)
    {
        $userId = Auth::id();
    
        // 1. Verify course exists and name matches
        $course = Course::findOrFail($courseId);
        if (Str::slug($course->name) !== $name) {
            return redirect()->route('course.module.open', [
                'name' => Str::slug($course->name),
                'courseId' => $courseId,
                'moduleTitle' => $moduleTitle,
                'moduleId' => $moduleId
            ]);
        }
    
        // 2. Check course access
        $userCourse = UserCourse::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    
        if (!$userCourse) {
            return redirect()->route('user.course')->with('error', 'You do not have access to this course.');
        }
    
        // 3. Load module with contents
        $module = Module::with('contents')
            ->where('id', $moduleId)
            ->where('course_id', $courseId)
            ->first();
    
        if (!$module) {
            return redirect()->back()->with('error', 'Module not found');
        }
    
        // 4. Verify module title matches URL
        if (Str::slug($module->title) !== $moduleTitle) {
            return redirect()->route('course.module.open', [
                'name' => $name,
                'courseId' => $courseId,
                'moduleTitle' => Str::slug($module->title),
                'moduleId' => $moduleId
            ]);
        }
    
        // 5. Update last opened (your existing logic)
        UserCourse::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->update(['last_opened' => now()]);
    
        return view('user.course_open', ['course' => $course,'module' => $module]);
    }

    public function clearHistory($courseId) {
        $userId = Auth::id();

        // Find the course progress record
        $userCourse = UserCourse::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->firstOrFail();

        // Clear the course history
        $userCourse->clearHistory();

        return redirect()->route('user.course')->with('success', 'Course history cleared successfully.');
    }

    public function delete($courseId) {
        $userId = Auth::id();

        // Find the course progress record
        $userCourse = UserCourse::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->firstOrFail();

        // Delete the course progress
        $userCourse->deleteCourse();

        return back()->with('success', 'Course Bookmark Removed successfully.');
    }

    public function submitExercise(Request $request) {
        $request->validate([
            'content_id' => 'required|integer|exists:module_contents,id',
            'is_correct' => 'required|boolean',
            'selected_answer' => 'required|string', // Validate the selected answer
        ]);

        $userId = Auth::id();
        $contentId = $request->content_id;

        // Find the module ID for the content
        $moduleId = ModuleContent::findOrFail($contentId)->module_id;

        // Save the exercise result in user_content_progress
        UserContentProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'module_content_id' => $contentId,
            ],
            [
                'module_id' => $moduleId,
                'is_correct' => $request->is_correct,
                'selected_answer' => $request->selected_answer,
                'submitted_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }
}