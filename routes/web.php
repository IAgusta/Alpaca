<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleContentController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::post('/upload-image', [ImageController::class, 'uploadImage'])->name('upload.image');
Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/about', function () { return view('about'); });
Route::get('/contact', function () { return view('contact'); });
Route::get('/price', function () { return view('prices'); });
Route::get('/news', function () { return view('news'); });
Route::get('/faqs', function () { return view('faqs'); });
Route::get('/robot-control', function () { return view('plugins.robotControl');})->name('plugins.robotControl');
Route::get('/monitoring', function () { return view('plugins.monitoring');})->name('plugins.monitoring');

Route::get('/dashboard', function () { return view('user.dashboard'); })->middleware(['auth', 'verified'])->name('user.dashboard');
Route::get('/course', function () { return view('user.course'); })->middleware(['auth', 'verified'])->name('user.course');
Route::get('/email/verify', function () { return view('auth.verify-email'); })->middleware('auth')->name('verification.notice');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/course', [AdminController::class, 'course'])->name('admin.course');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Course Routes
    Route::get('/courses', [CourseController::class, 'index'])->name('admin.courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('admin.courses.store');
    Route::get('/admin/courses/{course}/edit', [CourseController::class, 'edit'])->name('admin.courses.edit');
    Route::put('/admin/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('admin.courses.destroy');

    // Module Routes (inside a course)
    Route::prefix('/courses/{course}/modules')->name('admin.courses.modules.')->group(function () {
        Route::get('/', [ModuleController::class, 'index'])->name('index'); // List modules
        Route::get('/create', [ModuleController::class, 'create'])->name('create'); // Show create module form
        Route::post('/', [ModuleController::class, 'store'])->name('store'); // Store new module
        Route::delete('/{module}', [ModuleController::class, 'destroy'])->name('destroy'); // Delete module
        Route::get('/{module}/edit', [ModuleController::class, 'edit'])->name('edit'); // Show edit module form
        Route::put('/{module}', [ModuleController::class, 'update'])->name('update'); // Update module
    });

    Route::prefix('/courses/{course}/modules/{module}/contents')
    ->name('admin.courses.modules.contents.')
    ->group(function () {
        Route::get('/', [ModuleContentController::class, 'index'])->name('index');
        Route::post('/', [ModuleContentController::class, 'store'])->name('store');
        Route::get('/{moduleContent}/edit', [ModuleContentController::class, 'edit'])->name('edit'); // Show edit form
        Route::patch('/{moduleContent}', [ModuleContentController::class, 'update'])->name('update'); // Update content
        Route::delete('/{moduleContent}', [ModuleContentController::class, 'destroy'])->name('destroy');// Delete content
        Route::post('/reorder', [ModuleContentController::class, 'reorder'])->name('reorder'); // Reorder content
    });
});

