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

class UserCourseController extends Controller
{
    public function index() {
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

    public function detail($courseId) {
        $userId = Auth::id();
        // Get the course with its modules and contents
        $course = Course::with(['modules.contents'])->findOrFail($courseId);
        $userCourses = UserCourse::where('user_id', $userId)
        ->with('course')
        ->get();

        $savedCourses = UserCourse::where('course_id', $courseId)->count();
    
        return view('user.course_detail', compact('course', 'userCourses','savedCourses'));
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

        // Increment the popularity of the course
        $course->increment('popularity');

        return back()->with('success', 'Course added to Bookmark.');
    }

    public function open($courseId) {
        $userId = Auth::id();

        // Check if the user has access to the course
        $userCourse = UserCourse::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$userCourse) {
            return redirect()->route('user.course')->with('error', 'You do not have access to this course.');
        }

        // Load the course with its modules and contents
        $course = Course::with('modules.contents')->findOrFail($courseId);
        return view('user.course_open', compact('course'));
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

    public function showContent($courseId, $moduleId, $contentId) {
        $userId = Auth::id();

        // Mark the content as read
        UserContentProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'module_content_id' => $contentId,
            ],
            [
                'module_id' => $moduleId,
                'read' => true,
                'read_at' => now(),
            ]
        );

        // Load the content
        $content = ModuleContent::findOrFail($contentId);
        return view('user.content', compact('content'));
    }

    public function markModuleAsRead($moduleId)
    {
        $userId = Auth::id();

        // Ensure the module exists
        $module = Module::find($moduleId);
        if (!$module) {
            return response()->json(['success' => false, 'message' => 'Module not found.'], 404);
        }

        // Check if all contents in the module are completed
        $totalContents = $module->contents->count();
        $completedContents = UserContentProgress::where('user_id', $userId)
            ->where('module_id', $moduleId)
            ->where('read', true)
            ->count();

        if ($completedContents === $totalContents) {
            // Mark the module as completed in user_course_progress
            $userCourse = UserCourse::where('user_id', $userId)
                ->where('course_id', $module->course_id)
                ->first();

            if ($userCourse) {
                $userCourse->increment('completed_modules');

                // Check if all modules in the course are completed
                if ($userCourse->completed_modules === $userCourse->total_modules) {
                    $userCourse->update([
                        'course_completed' => true,
                        'course_completed_at' => now(),
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function getDrawerContent($moduleId)
    {
        $userId = Auth::id();

        // Fetch all read content for the module by joining with module_contents
        $readContents = DB::table('user_content_progress')
            ->join('module_contents', 'user_content_progress.module_content_id', '=', 'module_contents.id')
            ->where('user_content_progress.user_id', $userId)
            ->where('module_contents.module_id', $moduleId)
            ->where('user_content_progress.read', true)
            ->select('module_contents.title', 'module_contents.id') // Select the title column
            ->get();

        // Prepare the HTML for the drawer content
        $html = '';
        foreach ($readContents as $content) {
            $html .= '<li>';
            $html .= '<a href="#content-' . $content->id . '" class="text-blue-500 hover:underline">';
            $html .= $content->title; // Use the title column
            $html .= '</a>';
            $html .= '</li>';
        }

        if (empty($html)) {
            $html = '<li class="text-gray-500">No content read yet.</li>';
        }

        return response()->json(['html' => $html]);
    }

    public function getContentDetails($contentId)
    {
        $content = ModuleContent::findOrFail($contentId);

        return response()->json([
            'content' => [
                'id' => $content->id,
                'title' => $content->title, // Assuming 'title' is a field in your ModuleContent model
            ],
            'moduleId' => $content->module_id,
        ]);
    }
}