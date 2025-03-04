<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB max
        ]);
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
    
            // Generate a unique name for the image
            $imageName = time() . '.' . $image->getClientOriginalExtension();
    
            // Store the image in the 'public/images' directory
            $imagePath = $image->store('public/images', $imageName);
    
            // Generate the URL for the stored image
            $url = Storage::url($imagePath);
    
            return response()->json([
                'success' => true,
                'url' => $url, // e.g., /storage/images/12345.jpg
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'No image file uploaded.',
        ]);
    }
}