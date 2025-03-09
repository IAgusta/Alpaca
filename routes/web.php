<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleContentController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RobotController;

require __DIR__.'/auth.php';

Route::post('/set-esp32-ip', [RobotController::class, 'setEsp32Ip']);
Route::get('/robot/{command}', [RobotController::class, 'sendCommand']);
Route::get('/robot/speed', [RobotController::class, 'updateSpeed']);
Route::get('/robot/connect', [RobotController::class, 'connect']);

Route::post('/upload-image', [ImageController::class, 'uploadImage'])->name('upload.image');
Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/about', function () { return view('about'); });
Route::get('/contact', function () { return view('contact'); });
Route::get('/price', function () { return view('prices'); });
Route::get('/news', function () { return view('news'); });
Route::get('/faqs', function () { return view('faqs'); });
Route::get('/plugins/robot-control', function () { return view('plugins.robotControl');})->name('plugins.robotControl');
Route::get('/plugins/monitoring', function () { return view('plugins.monitoring');})->name('plugins.monitoring');
Route::get('/documentation', function(){ return view('plugins.documentation');})->name('documentation');
Route::get('/documentation-esp32', function () { return view('plugins.documentation.esp32');})->name('documentation.esp32');
Route::get('/documentation-esp8266', function () { return view('plugins.documentation.esp8266');})->name('documentation.esp8266');

Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/course', function () { return view('user.course'); })->middleware(['auth', 'verified'])->name('user.course');
Route::get('/email/verify', function () { return view('auth.verify-email'); })->middleware('auth')->name('verification.notice');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'only.admin'])->group(function () {
    Route::get('/admin/manage-user', [AdminController::class, 'manageUsers'])->name('admin.manage-user');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::put('/admin/users/{id}/toggle-active', [AdminController::class, 'toggleActive'])->name('admin.toggleActive');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
});

// Routes for admin and teach
Route::middleware(['auth', 'admin'])->group(function () {
    // Course Routes
    Route::get('/courses', [CourseController::class, 'index'])->name('admin.courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('admin.courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('admin.courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
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

    // Module Content Routes
    Route::prefix('/courses/{course}/modules/{module}/contents')
        ->name('admin.courses.modules.contents.')
        ->group(function () {
            Route::get('/', [ModuleContentController::class, 'index'])->name('index');
            Route::post('/', [ModuleContentController::class, 'store'])->name('store');
            Route::get('/{moduleContent}/edit', [ModuleContentController::class, 'edit'])->name('edit'); // Show edit form
            Route::patch('/{moduleContent}', [ModuleContentController::class, 'update'])->name('update'); // Update content
            Route::delete('/{moduleContent}', [ModuleContentController::class, 'destroy'])->name('destroy'); // Delete content
            Route::post('/reorder', [ModuleContentController::class, 'reorder'])->name('reorder'); // Reorder content
        });
});