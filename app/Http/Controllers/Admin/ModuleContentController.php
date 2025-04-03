<?php

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

    private function processQuillImages($content, Course $course, $oldContent = null)
    {
        // Extract existing images from old content (if provided)
        $existingImages = [];
        if ($oldContent) {
            preg_match_all('/<img src="[^"]+\/storage\/content_images\/[^"]+"/', $oldContent, $existingMatches);
            $existingImages = $existingMatches[0] ?? [];
        }
    
        preg_match_all('/<img src="data:image\/(png|jpeg|jpg|gif);base64,([^"]+)"/', $content, $matches, PREG_SET_ORDER);
    
        $courseFolder = 'course_' . $course->id;
        $newImages = [];
    
        foreach ($matches as $match) {
            $extension = $match[1];
            $base64Data = $match[2];
            
            if (!base64_decode($base64Data, true)) {
                continue;
            }
    
            $imageData = base64_decode($base64Data);
            $fileName = 'quill_' . time() . '_' . uniqid() . '.' . $extension;
            $storagePath = "content_images/{$courseFolder}/{$fileName}";
            
            Storage::disk('public')->makeDirectory("content_images/{$courseFolder}");
            
            try {
                Storage::disk('public')->put($storagePath, $imageData);
                $publicUrl = Storage::url($storagePath);
                $newImages[] = '<img src="' . $publicUrl . '"';
                $content = str_replace($match[0], '<img src="' . $publicUrl . '"', $content);
            } catch (\Exception $e) {
                Log::error("Image upload failed: " . $e->getMessage());
            }
        }
    
        // Delete images that existed in old content but not in new content
        if ($oldContent) {
            $imagesToDelete = array_diff($existingImages, $newImages);
            foreach ($imagesToDelete as $imgTag) {
                preg_match('/src="([^"]+)"/', $imgTag, $srcMatch);
                if (isset($srcMatch[1])) {
                    $path = parse_url($srcMatch[1], PHP_URL_PATH);
                    $relativePath = str_replace('/storage/', '', $path);
                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }
                }
            }
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
            $content = $this->processQuillImages($content, $course); // Added course parameter
            $data['content'] = $content;
        } else {
            $request->validate([
                'question' => 'required|string',
                'answers' => 'required|array',
                'answers.*.text' => 'required|string',
                'answers.*.correct' => 'nullable|boolean',
                'explanation' => 'nullable|string',
            ]);
    
            $question = $this->processQuillImages($request->input('question'), $course); // Added course parameter
            $answers = [];
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
        $content = ModuleContent::findOrFail($moduleContent);
        
        if ($content->content_type === 'content') {
            $request->validate([
                'title' => 'string|max:255',
                'content' => 'required|string',
            ]);
    
            // Pass old content to detect removed images
            $processedContent = $this->processQuillImages(
                $request->input('content'),
                $course,
                $content->content // Pass old content
            );
    
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
    
            $exerciseData = json_decode($content->content, true);
            $oldQuestion = $exerciseData['question'] ?? '';
    
            $processedQuestion = $this->processQuillImages(
                $request->input('question'),
                $course,
                $oldQuestion // Pass old question content
            );
    
            $exerciseData = [
                'question' => $processedQuestion,
                'answers' => $request->input('answers'),
                'explanation' => $request->input('explanation'),
            ];
    
            $content->update([
                'title' => $request->input('title'),
                'content' => json_encode($exerciseData),
            ]);
        }
    
        $module->touch();
        $course->touch();
    
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
    
        $module->touch();
        $course->touch();
    
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
            $path = parse_url($imageUrl, PHP_URL_PATH);
            
            if (str_contains($path, '/storage/content_images/')) {
                $relativePath = str_replace('/storage/', '', $path);
                
                // Delete the actual file
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }
                
                // Optional: Delete empty course folders
                $dirPath = dirname($relativePath);
                if (count(Storage::disk('public')->files($dirPath)) === 0) {
                    Storage::disk('public')->deleteDirectory($dirPath);
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

        $module->touch();
        $course->touch();
    
        return response()->json(['success' => true]);
    }
}