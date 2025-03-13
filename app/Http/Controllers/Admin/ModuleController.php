<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index($course_id)
    {
        $course = Course::findOrFail($course_id);
        $modules = $course->modules; // Get all modules for this course

        return view('admin.courses.modules.index', compact('course', 'modules'));
    }

    public function create($course_id)
    {
        $course = Course::findOrFail($course_id);
        return view('admin.courses.modules.create', compact('course'));
    }

    public function store(Request $request, $course_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $module = Module::create([
            'course_id' => $course_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.courses.index', $course_id)
        ->with('success', 'New Module Chapter is Added into ' . $module->course->name);
    }

    public function edit(Course $course, Module $module)
    {
        return view('admin.courses.modules.edit', compact('course', 'module'));
    }

    public function update(Request $request, Course $course, Module $module)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Update module
        $module->title = $request->title;
        $module->description = $request->description;
        $module->save();

        return redirect()->route('admin.courses.index', $course->id)->with('success', 'Module updated successfully!');
    }

    public function destroy(Course $course, Module $module)
    {
        $module->delete(); // Delete the module

        return redirect()->route('admin.courses.index', $course->id)
                        ->with('success', 'Module deleted successfully.');
    }
}