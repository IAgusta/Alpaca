<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');

        // Generate a unique cache key based on the query parameters and page
        $page = $request->input('page', 1);
        $cacheKey = "courses.list.{$search}.{$sort}.{$direction}.{$page}." . Auth::id();

        $courses = Cache::tags(['courses'])->remember($cacheKey, now()->addMinutes(30), function () use ($search, $sort, $direction) {
            $query = Course::with(['authorUser', 'userProgress'])->withCount('userProgress');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('theme', 'like', '%' . $search . '%')
                      ->orWhereHas('authorUser', function($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
                });
            }

            if (Auth::user()->role === 'trainer') {
                $query->where('author', Auth::id());
            }

            return $query->orderBy($sort, $direction)->paginate(11)->withQueryString();
        });
        
        if ($request->ajax()) {
            // Include new course button in the AJAX response
            $html = view('admin.courses.component.available_course', compact('courses'))->render();
            $createButton = view('admin.courses.component.create_button')->render();
            return $createButton . $html;
        }

        return view('admin.courses.index', compact('courses', 'search', 'sort', 'direction'));
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

        $course = Course::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image' => $imagePath,
            'author' => Auth::id(),
            'theme' => $request->input('theme'),
        ]);

        // Clear relevant caches
        Cache::tags(['courses'])->flush();

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
    
        if (Auth::user()->role !== 'admin' && intval($course->author) !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        try {
            $plainPassword = $course->lock_password ? Crypt::decryptString($course->lock_password) : null;
            Log::info('Decrypted password: ' . $plainPassword); // Log the decrypted password
        } catch (\Exception $e) {
            $plainPassword = null;
            Log::error('Decryption failed: ' . $e->getMessage()); // Log any decryption errors
        }
        return view('admin.courses.edit', [
            'course' => $course,
            'plainPassword' => $plainPassword // Ensure this is passed to the view
        ]);
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

        // Clear relevant caches
        Cache::tags(['courses'])->flush();
    
        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Request $request, Course $course)
    {
        // Allow only admins or course owners to delete
        if (Auth::user()->role !== 'admin' && intval($course->author) !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validate using display_name instead of full name
        $request->validate([
            'course_name' => ['required', 'string', 'in:' . $course->display_name],
        ]);

        // Soft delete the course
        $course->delete();

        // Clear relevant caches
        Cache::tags(['courses'])->flush();

        // Redirect with a success message
        return redirect()->route('admin.courses.index')->with('success', 'Course moved to Trash Bin.');
    }
    
    public function forceDestroy($id)
    {
        $course = Course::withTrashed()->findOrFail($id);

        // Allow only admins to permanently delete
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Delete the course image if it exists
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        // Permanently delete the course
        $course->forceDelete();

        return redirect()->route('admin.courses.index')->with('success', 'Course permanently deleted.');
    }

    public function restore($id)
    {
        $course = Course::withTrashed()->findOrFail($id);

        // Allow only admins or course owners to restore
        if (Auth::user()->role !== 'admin' && intval($course->author) !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $course->restore();

        return redirect()->route('admin.courses.index')->with('success', 'Course restored successfully.');
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
            'lock_password' => Crypt::encryptString($password), // Encrypt password
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
    
        try {
            $decryptedPassword = Crypt::decryptString($course->lock_password);
        } catch (\Exception $e) {
            return redirect()->route('admin.courses.index')->with('error', 'Decryption failed.');
        }
    
        if ($request->lock_password === $decryptedPassword) {
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
