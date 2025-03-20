<?php

// app/Http/Controllers/ModuleContentController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModuleContent;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class ModuleContentController extends Controller
{
    public function index(Course $course, Module $module)
    {
        $contents = $module->contents()->orderBy('position')->get();
        return view('admin.courses.modules.contents.index', compact('course', 'module', 'contents'));
    } 

    public function store(Request $request, Course $course, Module $module)
    {
        $data = $request->validate([
            'content_type' => 'required|in:content,exercise',
        ]);
    
        if ($request->content_type == 'content') {
            // If "Isi" (Content), store it as plain HTML
            $data['content'] = $request->validate([
                'content' => 'required|string',
            ])['content'];
        } else {
            // If "Latihan" (Exercise), convert data to JSON
            $answers = [];
            foreach ($request->input('answers') as $answer) {
                $answers[] = [
                    'text' => $answer['text'],
                    'correct' => $answer['correct'] ?? false, // Ensure "correct" key exists
                ];
            }
    
            $data['content'] = json_encode([
                'question' => $request->input('question'),
                'answers' => $answers, // Use the processed answers array
                'explanation' => $request->input('explanation'),
            ]);
        }
    
        // ✅ Assign the next position automatically
        $lastPosition = $module->contents()->max('position') ?? 0;
        $data['position'] = $lastPosition + 1;
        $data['author'] = Auth::id(); // Set the author to the logged-in user
    
        // ✅ Insert into database
        $module->contents()->create($data);

        // ✅ Update the module's updated_at timestamp
        $module->touch();
        $course->touch();
    
        return back()->with('success', 'Content added successfully!');
    }

    public function edit(Course $course, Module $module, $moduleContent)
    {
        // Find the module content by ID
        $content = ModuleContent::findOrFail($moduleContent);

        // ✅ Update the module's updated_at timestamp
        $module->touch();
        $course->touch();

        // Pass the content, course, and module to the edit view
        return view('admin.courses.modules.contents.edit', [
            'course' => $course,
            'module' => $module,
            'content' => $content,
        ]);
    }
    

    public function update(Request $request, Course $course, Module $module, $moduleContent)
    {
        // Find the module content by ID
        $content = ModuleContent::findOrFail($moduleContent);
    
        // Validate the request based on content type
        if ($content->content_type === 'content') {
            $request->validate([
                'content' => 'required|string',
            ]);
    
            // Update the content for 'content' type
            $content->update([
                'content' => $request->input('content'),
            ]);
        } elseif ($content->content_type === 'exercise') {
            $request->validate([
                'question' => 'required|string',
                'answers' => 'required|array',
                'answers.*.text' => 'required|string',
                'answers.*.correct' => 'nullable|boolean',
                'explanation' => 'nullable|string',
            ]);
    
            // Prepare the exercise data
            $exerciseData = [
                'question' => $request->input('question'),
                'answers' => $request->input('answers'),
                'explanation' => $request->input('explanation'),
            ];
    
            // Update the content for 'exercise' type
            $content->update([
                'content' => json_encode($exerciseData), // Store as JSON
            ]);
        }
    
        // ✅ Update the course and module's updated_at timestamp
        $module->touch();
        $course->touch();
    
        // Redirect with success message
        return redirect()->route('admin.courses.modules.contents.index', ['course' => $course, 'module' => $module])
                         ->with('success', 'Content updated successfully.');
    }

    public function destroy(Request $request, Course $course, Module $module, ModuleContent $moduleContent)
    {
        if ($moduleContent->module_id !== $module->id) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }
    
        $moduleContent->delete();
    
        // ✅ Update the module's updated_at timestamp
        $module->touch();
        $course->touch();
    
        // If it's an AJAX request, return JSON
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
    
        // Otherwise, redirect back to the content page with a success message
        return redirect()->route('admin.courses.modules.contents.index', [
            'course' => $course->id,
            'module' => $module->id
        ])->with('success', 'Content deleted successfully!');
    }
    
    

    public function reorder(Request $request, Course $course, Module $module)
    {
        $positions = $request->input('positions');
    
        foreach ($positions as $position) {
            ModuleContent::where('id', $position['id'])
                ->where('module_id', $module->id)
                ->update(['position' => $position['position']]);
        }

        // ✅ Update the module's updated_at timestamp
        $module->touch();
        $course->touch();
    
        return response()->json(['success' => true]);
    }
    
}
