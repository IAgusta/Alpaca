<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleContentController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () { return view('welcome'); });
Route::get('/about', function () { return view('about'); });
Route::get('/contact', function () { return view('contact'); });
Route::get('/price', function () { return view('prices'); });
Route::get('/course-preview', function () { return view('preview'); });
Route::get('/faqs', function () { return view('faqs'); });

Route::get('/dashboard', function () {
    return view('user.dashboard');
})->middleware(['auth', 'verified'])->name('user.dashboard');

Route::get('/course', function () {
    return view('user.course');
})->middleware(['auth', 'verified'])->name('user.course');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/course', [AdminController::class, 'course'])->name('admin.course');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

require __DIR__.'/auth.php';

Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Course Routes
    Route::get('/courses', [CourseController::class, 'index'])->name('admin.courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('admin.courses.store');
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
        Route::get('/create', [ModuleContentController::class, 'create'])->name('create'); // Show create form
        Route::post('/', [ModuleContentController::class, 'store'])->name('store');
        Route::get('/{moduleContent}/edit', [ModuleContentController::class, 'edit'])->name('edit'); // Show edit form
        Route::patch('/{moduleContent}', [ModuleContentController::class, 'update'])->name('update'); // Update content
        Route::delete('/{moduleContent}', [ModuleContentController::class, 'destroy'])->name('destroy'); // Delete content
        Route::post('/reorder', [ModuleContentController::class, 'reorder'])->name('reorder'); // Reorder content
    });
});

