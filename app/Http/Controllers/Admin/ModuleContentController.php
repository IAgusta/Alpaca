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
            'content_type' => 'required|in:text,image,video,code',
            'content' => 'required|string',
            'position' => 'required|integer',
        ]);
    
        // Ensure the module ID is set
        $data['module_id'] = $module->id;
    
        // Create the content
        $moduleContent = ModuleContent::create($data);
    
        return back()->with('success', 'Content added successfully!');
    }
    

    public function update(Request $request, ModuleContent $moduleContent)
    {
        $data = $request->validate([
            'content_type' => 'required|in:text,image,video,code',
            'content' => 'required|string',
            'position' => 'required|integer',
        ]);

        $moduleContent->update($data);
        return back()->with('success', 'Content updated successfully!');
    }

    public function destroy(ModuleContent $moduleContent)
    {
        $moduleContent->delete();
        return back()->with('success', 'Content deleted successfully!');
    }

    public function reorder(Request $request)
    {
        foreach ($request->positions as $index => $id) {
            ModuleContent::where('id', $id)->update(['position' => $index + 1]);
        }
        return response()->json(['success' => true]);
    }
}
