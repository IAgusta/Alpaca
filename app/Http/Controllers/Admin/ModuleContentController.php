<?php

// app/Http/Controllers/ModuleContentController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModuleContent;
use App\Models\Module;
use App\Models\Course;

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
    
        // ✅ Insert into database
        $module->contents()->create($data);
    
        return back()->with('success', 'Content added successfully!');
    }

    public function edit($course, $module, $moduleContent)
    {
        // Find the module content by ID
        $content = ModuleContent::findOrFail($moduleContent);

        // Pass the content, course, and module to the edit view
        return view('admin.courses.modules.contents.edit', [
            'course' => $course,
            'module' => $module,
            'content' => $content,
        ]);
    }
    

    public function update(Request $request, $course, $module, $moduleContent)
    {
        // Find the module content by ID
        $content = ModuleContent::findOrFail($moduleContent);
    
        // Validate the request
        $request->validate([
            'content_type' => 'required|string',
            'content' => 'required|string',
        ]);
    
        // Update the content
        $content->update([
            'content_type' => $request->input('content_type'),
            'content' => $request->input('content'),
        ]);
    
        return redirect()->route('admin.courses.modules.contents.index', ['course' => $course, 'module' => $module])
            ->with('success', 'Content updated successfully.');
    }

    public function destroy(Course $course, Module $module, ModuleContent $moduleContent)
    {
        if ($moduleContent->module_id !== $module->id) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }
    
        $moduleContent->delete();
        return response()->json(['success' => true]);
    }
    

    public function reorder(Request $request, Course $course, Module $module)
    {
        $positions = $request->input('positions');
    
        foreach ($positions as $position) {
            ModuleContent::where('id', $position['id'])
                ->where('module_id', $module->id)
                ->update(['position' => $position['position']]);
        }
    
        return response()->json(['success' => true]);
    }
    
}
