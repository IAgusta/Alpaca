<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleContentController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RobotController;
use App\Http\Controllers\UserCourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Auth;
use App\Models\UserModel;

require __DIR__.'/auth.php';

Route::post('/set-esp32-ip', [RobotController::class, 'setEsp32Ip']);
Route::get('/robot/{command}', [RobotController::class, 'sendCommand']);
Route::get('/robot/speed', [RobotController::class, 'updateSpeed']);
Route::get('/robot/connect', [RobotController::class, 'connect']);

Route::post('/upload-image', [ImageController::class, 'uploadImage'])->name('upload.image');
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', function () { return view('contact');})->name('contact');
Route::get('/price', function () { return view('prices');})->name('prices');
Route::get('/faq', function () { return view('faqs');})->name('faq');
Route::get('/terms', function () { return view('terms');})->name('terms');
Route::get('/privacy-policy', function () { return view('privacy-policy');})->name('privacy-policy');
Route::get('/news', function () { return view('news');})->name('news');
Route::get('/plugins/robot-control', function () { return view('plugins.robotControl');})->name('plugins.robotControl');
Route::get('/plugins/search-users', function () { return view('plugins.monitoring');})->name('plugins.monitoring');
Route::get('/documentation', function(){ return view('plugins.documentation');})->name('documentation');
Route::get('/documentation-esp32', function () { return view('plugins.documentation.esp32');})->name('documentation.esp32');
Route::get('/documentation-esp8266', function () { return view('plugins.documentation.esp8266');})->name('documentation.esp8266');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/email/verify', function () { return view('auth.verify-email'); })->middleware('auth')->name('verification.notice');

Route::middleware(['auth'])->group(function () {
    Route::get('/courses', [UserCourseController::class, 'index'])->name('user.course');
    Route::post('/courses/add/{courseId}', [UserCourseController::class, 'add'])->name('user.course.add');
    Route::get('/courses/{name}/{courseId}/detail', [UserCourseController::class, 'detail'])->name('user.course.detail');
    Route::get('/course/{name}/{courseId}/{moduleTitle}/{moduleId}', [UserCourseController::class, 'open'])->name('course.module.open');
    Route::post('/module-progress/{moduleId}/toggle', [UserCourseController::class, 'toggle'])->name('module.progress.toggle');
    Route::post('/courses/{courseId}/toggle-all', [UserCourseController::class, 'toggleAllModules'])->name('user.course.toggleAll');
    Route::post('/courses/clear-history/{courseId}', [UserCourseController::class, 'clearHistory'])->name('user.course.clearHistory');
    Route::delete('/courses/delete/{courseId}', [UserCourseController::class, 'delete'])->name('user.course.delete');
    Route::post('/exercise/submit', [UserCourseController::class, 'submitExercise'])->name('user.exercise.submit');
    Route::post('/mark-module-as-read/{moduleId}', [UserCourseController::class, 'markModuleAsRead']);
    Route::get('/get-drawer-content/{moduleId}', [UserCourseController::class, 'getDrawerContent']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'only.admin'])->group(function () {
    Route::get('/admin/manage-user', [AdminController::class, 'manageUsers'])->name('admin.manage-user');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
});

// Routes for admin and trainer
Route::middleware(['auth', 'admin'])->group(function () {
    // Course Routes
    Route::get('/courses-index', [CourseController::class, 'index'])->name('admin.courses.index');
    Route::post('/courses/storedtoDB', [CourseController::class, 'store'])->name('admin.courses.store');
    Route::put('/courses/{course}/updatefromDB', [CourseController::class, 'update'])->name('admin.courses.update');
    Route::post('/courses/{course}/locked', [CourseController::class, 'lockCourse'])->name('course.lockCourse');
    Route::post('/courses/{course}/unlock', [CourseController::class, 'unlockCourse'])->name('course.unlockCourse');
    Route::delete('/courses/{course}/destroyfromDB', [CourseController::class, 'destroy'])->name('admin.courses.destroy');

    // Module Routes (inside a course)
    Route::prefix('/courses-index/{course}/modules')->name('admin.courses.modules.')->group(function () {
        Route::post('/', [ModuleController::class, 'store'])->name('store'); // Store new module
        Route::delete('/{module}/destroyfromDB', [ModuleController::class, 'destroy'])->name('destroy'); // Delete module
        Route::put('/{module}/updatedfromDB', [ModuleController::class, 'update'])->name('update'); // Update module
    });

    // Module Content Routes
    Route::prefix('/courses-index/{course}/modules/{module}/contents')
        ->name('admin.courses.modules.contents.')
        ->group(function () {
            Route::get('/', [ModuleContentController::class, 'index'])->name('index');
            Route::post('/', [ModuleContentController::class, 'store'])->name('store');
            Route::get('/{moduleContent}/edit', [ModuleContentController::class, 'edit'])->name('edit'); // Show edit form
            Route::patch('/{moduleContent}/updatedfromDB', [ModuleContentController::class, 'update'])->name('update'); // Update content
            Route::delete('/{moduleContent}/destroyfromDB', [ModuleContentController::class, 'destroy'])->name('destroy'); // Delete content
            Route::post('/reorder/changePlace', [ModuleContentController::class, 'reorder'])->name('reorder'); // Reorder content
        });
});