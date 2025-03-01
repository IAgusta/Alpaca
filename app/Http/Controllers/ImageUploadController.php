<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class ImageUploadController extends Controller
{
    public function store(Request $request, ImageManager $imageManager)
    {
        $image = $request->file('image');

        // Create image instance with GD driver
        $img = $imageManager->read($image)->resize(300, 300);

        // Save the image
        $img->save(storage_path('app/public/resized_image.jpg'));

        return response()->json(['message' => 'Image uploaded successfully']);
    }
}
