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
Route::get('/about', [LandingPageController::class, 'index'])->name('about');
Route::get('/contact', function () { return view('contact');})->name('contact');
Route::get('/price', function () { return view('prices');})->name('prices');
Route::get('/faq', function () { return view('faqs');})->name('faq');
Route::get('/terms', function () { return view('terms');})->name('terms');
Route::get('/privacy-policy', function () { return view('privacy-policy');})->name('privacy-policy');
Route::get('/news', function () { return view('news');})->name('news');
Route::get('/tools/robot-control', function () { return view('plugins.robotControl');})->name('plugins.robotControl');
Route::get('/find-users', function () { return view('plugins.search_user');})->name('plugins.search-users');
Route::get('/documentation', function(){ return view('plugins.documentation');})->name('documentation');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/email/verify', function () { return view('auth.verify-email'); })->middleware('auth')->name('verification.notice');
Route::get('/settings', function() {return view('settings');})->name('settings');
Route::get('/forum', function(){ return view('plugins.forum');})->name('forum');

Route::middleware(['auth'])->group(function () {
    // User course routes
    Route::get('/courses', [UserCourseController::class, 'index'])->name('user.course');
    Route::get('/courses/feed', [UserCourseController::class, 'feed'])->name('course.feed');
    Route::get('/courses/library', [UserCourseController::class, 'userFeed'])->name('user.course.library');
    Route::post('/courses/add/{courseId}', [UserCourseController::class, 'add'])->name('user.course.add');
    
    Route::get('/{name}/{courseId}', [UserCourseController::class, 'detail'])
        ->name('user.course.detail')
        ->where(['courseId' => '[0-9]+', 'name' => '[a-zA-Z0-9-]+']);
    Route::get('/{name}/{courseId}/chapter/{moduleTitle}/{moduleId}', [UserCourseController::class, 'open'])
        ->name('course.module.open')
        ->where(['courseId' => '[0-9]+', 'moduleId' => '[0-9]+', 'name' => '[a-zA-Z0-9-]+']);

    Route::post('/module-progress/{moduleId}/toggle', [UserCourseController::class, 'toggle'])->name('module.progress.toggle');
    Route::post('/courses/{courseId}/toggle-all', [UserCourseController::class, 'toggleAllModules'])->name('user.course.toggleAll');
    Route::post('/courses/clear-history/{courseId}', [UserCourseController::class, 'clearHistory'])->name('user.course.clearHistory');
    Route::delete('/courses/delete/{courseId}', [UserCourseController::class, 'delete'])->name('user.course.delete');
    Route::post('/exercise/submit', [UserCourseController::class, 'submitExercise'])->name('user.exercise.submit');
    Route::post('/mark-module-as-read/{moduleId}', [UserCourseController::class, 'markModuleAsRead']);
    Route::get('/get-drawer-content/{moduleId}', [UserCourseController::class, 'getDrawerContent']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update-links', [ProfileController::class, 'updateSocialMediaLinks'])->name('profile.update.link');
    Route::post('/profile/update-images', [ProfileController::class, 'updateProfileImage'])->name('profile.update.images');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'only.admin'])->group(function () {
    Route::get('/admin/manage-user', [AdminController::class, 'manageUsers'])->name('admin.manage-user');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin course management routes
    Route::prefix('manage')->group(function () {
        // Course Routes
        Route::get('/courses-index', [CourseController::class, 'index'])->name('admin.courses.index');
        Route::post('/courses/store', [CourseController::class, 'store'])->name('admin.courses.store');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
        Route::post('/courses/{course}/locked', [CourseController::class, 'lockCourse'])->name('course.lockCourse');
        Route::post('/courses/{course}/unlock', [CourseController::class, 'unlockCourse'])->name('course.unlockCourse');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('admin.courses.destroy');

        // Module Routes
        Route::prefix('courses/{course}/modules')->name('admin.courses.modules.')->group(function () {
            Route::post('/', [ModuleController::class, 'store'])->name('store');
            Route::put('/{module}', [ModuleController::class, 'update'])->name('update');
            Route::delete('/{module}', [ModuleController::class, 'destroy'])->name('destroy');
            Route::post('/reorder', [ModuleController::class, 'reorder'])->name('reorder');
            
            // Module Content Routes
            Route::prefix('{module}/contents')->name('contents.')->group(function () {
                Route::get('/', [ModuleContentController::class, 'index'])->name('index');
                Route::post('/', [ModuleContentController::class, 'store'])->name('store');
                Route::get('/{moduleContent}/edit', [ModuleContentController::class, 'edit'])->name('edit');
                Route::patch('/{moduleContent}', [ModuleContentController::class, 'update'])->name('update');
                Route::delete('/{moduleContent}', [ModuleContentController::class, 'destroy'])->name('destroy');
                Route::post('/reorder', [ModuleContentController::class, 'reorder'])->name('reorder');
            });
        });
    });
});

// API route for user search
Route::get('/api/search-users', [ProfileController::class, 'search'])->name('api.users.search');
Route::get('/api/all-users', [ProfileController::class, 'getAllUsers'])->name('api.users.all');

// Update username route constraint to exclude numeric patterns that could match course IDs
Route::get('/{username}', [ProfileController::class, 'show'])
    ->name('profile.show')
    ->where('username', '^(?!.*[0-9]+$)(?!dashboard$|about$|contact$|price$|faq$|terms$|privacy-policy$|news$|documentation$|documentation-esp32$|documentation-esp8266$|plugins$|find-users$|courses$|profile$|admin$|manage$|email$|courses-index$|forum$|settings$).*$');