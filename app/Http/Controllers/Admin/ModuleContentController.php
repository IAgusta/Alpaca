<?php

// app/Http/Controllers/ModuleContentController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModuleContent;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ModuleContentController extends Controller
{
    public function index(Course $course, Module $module)
    {
        $contents = $module->contents()->orderBy('position')->get();
        return view('admin.courses.modules.contents.index', compact('course', 'module', 'contents'));
    } 

    private function processQuillImages($content)
    {
        preg_match_all('/<img src="data:image\/(png|jpeg|jpg|gif);base64,([^"]+)"/', $content, $matches, PREG_SET_ORDER);
    
        foreach ($matches as $match) {
            $extension = $match[1];
            $base64Data = $match[2];
            $imageData = base64_decode($base64Data);
    
            $fileName = 'quill_' . time() . '_' . uniqid() . '.' . $extension;
            $storagePath = 'content_images/' . $fileName;
            
            // Store using absolute path
            Storage::disk('public')->put($storagePath, $imageData);
            
            // Generate URL - use one of these options:
            // Option 1: Using asset()
            // $publicUrl = asset('storage/' . $storagePath);
            
            // Option 2: Using Storage::url()
            $publicUrl = Storage::url($storagePath);
            
            $content = str_replace($match[0], '<img src="' . $publicUrl . '"', $content);
        }
    
        return $content;
    }

    public function store(Request $request, Course $course, Module $module)
    {
        $data = $request->validate([
            'content_type' => 'required|in:content,exercise',
            'title' => 'required|string|max:255',
        ]);
    
        if ($request->content_type == 'content') {
            $content = $request->validate(['content' => 'required|string'])['content'];
    
            $content = $this->processQuillImages($content);
    
            $data['content'] = $content;
        } else {
            $answers = [];
            $question = $this->processQuillImages($request->input('question'));
            foreach ($request->input('answers') as $answer) {
                $answers[] = [
                    'text' => $answer['text'],
                    'correct' => $answer['correct'] ?? false,
                ];
            }
    
            $data['content'] = json_encode([
                'question' => $question,
                'answers' => $answers,
                'explanation' => $request->input('explanation'),
            ]);
        }
    
        $lastPosition = $module->contents()->max('position') ?? 0;
        $data['position'] = $lastPosition + 1;
        $data['author'] = Auth::id();
    
        $module->contents()->create($data);
    
        $module->touch();
        $course->touch();
    
        return back()->with('success', 'Content added successfully!');
    }
    

    public function edit(Course $course, Module $module, $moduleContent)
    {
        // Find the module content by ID
        $content = ModuleContent::findOrFail($moduleContent);

        $module->touch();
        $course->touch();

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
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);
    
            // ✅ Process Quill images before updating
            $processedContent = $this->processQuillImages($request->input('content'));
    
            // Update the content
            $content->update([
                'title' => $request->input('title'),
                'content' => $processedContent,
            ]);
        } elseif ($content->content_type === 'exercise') {
            $request->validate([
                'title' => 'required|string|max:255',
                'question' => 'required|string',
                'answers' => 'required|array',
                'answers.*.text' => 'required|string',
                'answers.*.correct' => 'nullable|boolean',
                'explanation' => 'nullable|string',
            ]);
    
            // ✅ Process Quill images in the question field
            $processedQuestion = $this->processQuillImages($request->input('question'));
    
            // Prepare the exercise data
            $exerciseData = [
                'question' => $processedQuestion,
                'answers' => $request->input('answers'),
                'explanation' => $request->input('explanation'),
            ];
    
            // Update the content
            $content->update([
                'title' => $request->input('title'),
                'content' => json_encode($exerciseData),
            ]);
        }
    
        // ✅ Update timestamps
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
    
        $this->deleteImagesFromContent($moduleContent->content);
    
        $moduleContent->delete();
    
        // ✅ Update timestamps
        $module->touch();
        $course->touch();
    
        // If it's an AJAX request, return JSON
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
    
        return redirect()->route('admin.courses.modules.contents.index', [
            'course' => $course->id,
            'module' => $module->id
        ])->with('success', 'Content deleted successfully!');
    }
    
    private function deleteImagesFromContent($content)
    {
        preg_match_all('/<img src="([^"]+)"/', $content, $matches);
    
        foreach ($matches[1] as $imageUrl) {
            // Parse URL to get path
            $path = parse_url($imageUrl, PHP_URL_PATH);
            
            // Convert URL path to storage path
            if (str_contains($path, '/storage/content_images/')) {
                $relativePath = str_replace('/storage/', '', $path);
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }
            }
        }
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
