<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Module;
use App\Models\ModuleContent;
use App\Models\UserCourse;
use App\Models\UserContentProgress;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserCourseController extends Controller
{
    public function index() 
    {
        $userId = Auth::id();
        // Update total_modules for all user courses
        $userCourses = UserCourse::where('user_id', $userId)->get();
        foreach ($userCourses as $userCourse) {
            $currentTotalModules = Course::find($userCourse->course_id)->modules->count();
            if ($userCourse->total_modules !== $currentTotalModules) {
                $userCourse->update(['total_modules' => $currentTotalModules]);
            }
        }

        // Get all user courses with updated data
        $userCourses = UserCourse::where('user_id', $userId)
            ->with('course')
            ->get();

        // Get available courses that the user hasn't added yet
        $availableCourses = Course::whereNotIn('id', $userCourses->pluck('course_id'))
            ->when(auth::user()->role === 'user', function ($query) {
                $query->whereRaw("LOWER(name) NOT LIKE '%test%'");
            })
            ->get();

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

        // Fetch user progress for this course
        $courseProgress = UserCourse::where('user_id', $userId)
        ->where('course_id', $courseId)
        ->first();
    
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
    
        return view('user.course_detail', compact('course', 'userCourses', 'savedCourses', 'courseProgress'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'lock_password' => 'nullable|string',
        ]);
    
        $userId = Auth::id();
        $course = Course::with('modules')->findOrFail($request->course_id);
    
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
    
        // Check if the course is already added
        $userCourse = UserCourse::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->first();
    
        if (!$userCourse) {
            // Count already read modules
            $completedModules = UserModel::where('user_id', $userId)
                ->whereHas('module', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->where('read', true)
                ->count();
    
            $totalModules = $course->modules->count();
            $courseCompleted = ($completedModules === $totalModules);
    
            // Add the course to the user's courses
            UserCourse::create([
                'user_id' => $userId,
                'course_id' => $course->id,
                'total_modules' => $totalModules,
                'completed_modules' => $completedModules,
                'progress' => ($totalModules > 0 ? ($completedModules / $totalModules) * 100 : 0),
                'course_completed' => $courseCompleted,
                'course_completed_at' => $courseCompleted ? now() : null,
            ]);
        }
    
        // Update course popularity
        $course->timestamps = false; // Disable timestamp updates
        $course->updateQuietly(['popularity' => $course->popularity + 1]); // Update quietly
    
        return back()->with('success', 'Course added to Bookmark.');
    }

    public function open($name, $courseId, $moduleTitle, $moduleId)
    {
        $userId = Auth::id();
    
        // Fetch the course
        $course = Course::findOrFail($courseId);
        if (Str::slug($course->name) !== $name) {
            return redirect()->route('course.module.open', [
                'name' => Str::slug($course->name),
                'courseId' => $courseId,
                'moduleTitle' => $moduleTitle,
                'moduleId' => $moduleId
            ]);
        }
    
        // Fetch the module
        $module = Module::with('contents')
            ->where('id', $moduleId)
            ->where('course_id', $courseId)
            ->first();
    
        if (!$module) {
            return redirect()->back()->with('error', 'Module not found');
        }
    
        // Verify module title matches URL
        if (Str::slug($module->title) !== $moduleTitle) {
            return redirect()->route('course.module.open', [
                'name' => $name,
                'courseId' => $courseId,
                'moduleTitle' => Str::slug($module->title),
                'moduleId' => $moduleId
            ]);
        }
    
        // Check if the course is already added to user_course_progress
        $courseProgress = UserCourse::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    
        if ($courseProgress) {
            // Update last opened timestamp
            $courseProgress->update(['last_opened' => now()]);
    
            // Update course progress immediately
            $totalModules = Module::where('course_id', $courseId)->count();
            $completedModules = UserModel::where('user_id', $userId)
                ->whereIn('module_id', Module::where('course_id', $courseId)->pluck('id'))
                ->where('read', true)
                ->count();
    
            $courseProgress->update([
                'completed_modules' => $completedModules,
                'course_completed' => $completedModules == $totalModules,
                'course_completed_at' => $completedModules == $totalModules ? now() : null,
            ]);
        }
    
        // Mark the module as read in user_module_progress
        UserModel::updateOrCreate(
            ['user_id' => $userId, 'module_id' => $moduleId],
            ['read' => true]
        );
    
        // Ensure progress is updated in case of new read history
        if ($courseProgress) {
            $this->updateCourseProgress($userId, $courseId);
        }
    
        return view('user.course_open', ['course' => $course, 'module' => $module]);
    }

    private function updateCourseProgress($userId, $courseId)
    {
        // Check if the user has saved the course
        $userHasCourse = UserCourse::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    
        // If user hasn't saved the course, do nothing (skip updating user_course_progress)
        if (!$userHasCourse) {
            return;
        }
    
        // Count total modules in the course
        $totalModules = Module::where('course_id', $courseId)->count();
    
        // Count read modules
        $completedModules = UserModel::where('user_id', $userId)
            ->whereHas('module', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->where('read', true)
            ->count();
    
        // Find or create user course progress
        $courseProgress = UserCourse::firstOrNew([
            'user_id' => $userId,
            'course_id' => $courseId,
        ]);
    
        $courseProgress->total_modules = $totalModules;
        $courseProgress->completed_modules = $completedModules;
        $courseProgress->course_completed = ($completedModules === $totalModules);
        $courseProgress->course_completed_at = $courseProgress->course_completed ? now() : null;
        $courseProgress->save();
    }
    
    public function toggleAllModules($courseId)
    {
        $userId = Auth::id();
    
        // Fetch all modules for the course
        $modules = Module::where('course_id', $courseId)->pluck('id');
    
        // Check if all modules are already read
        $allRead = UserModel::whereIn('module_id', $modules)
            ->where('user_id', $userId)
            ->where('read', true)
            ->count() === count($modules);
    
        // New status: If all are read, set to unread; otherwise, mark all as read
        $newStatus = !$allRead;
    
        // Update user module progress
        UserModel::whereIn('module_id', $modules)
            ->where('user_id', $userId)
            ->update(['read' => $newStatus]);
    
        // ✅ Update user course progress
        $this->updateCourseProgress($userId, $courseId);
    
        // ✅ Fetch the latest progress after the update
        $progress = UserCourse::where('user_id', $userId)->where('course_id', $courseId)->first();
    
        return response()->json([
            'success' => true,
            'newStatus' => $newStatus ? 'read' : 'unread',
            'completed_modules' => $progress->completed_modules ?? 0,
            'total_modules' => $progress->total_modules ?? 0,
            'course_completed' => $progress->course_completed ?? false,
        ]);
    }

    public function toggle($moduleId)
    {
        $userId = Auth::id();
    
        // Find the progress entry
        $progress = UserModel::where('user_id', $userId)
            ->where('module_id', $moduleId)
            ->first();
    
        // Toggle read status
        if ($progress) {
            $progress->read = !$progress->read; // Toggle between 1 and 0
            $progress->save();
        } else {
            // Create a new progress entry if it doesn't exist
            UserModel::create([
                'user_id' => $userId,
                'module_id' => $moduleId,
                'read' => true,
            ]);
        }
    
        // Get the course ID from the module
        $module = Module::findOrFail($moduleId);
        $courseId = $module->course_id;
    
        // Update User Course Progress
        $this->updateCourseProgress($userId, $courseId);
    
        return response()->json([
            'success' => true,
            'status' => $progress->read ? "read" : "unread"
        ]);
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