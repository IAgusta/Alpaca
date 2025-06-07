<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RobotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\NewsController;

// Include all route files
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/course.php';

// Robot control routes
Route::middleware(['auth'])->prefix('robot')->group(function () {
    Route::post('/set-esp32-ip', [RobotController::class, 'setEsp32Ip'])->name('robot.set-ip');
    Route::get('/connect', [RobotController::class, 'connect'])->name('robot.connect');
    Route::get('/command/{command}', [RobotController::class, 'sendCommand'])->name('robot.command');
    Route::get('/speed', [RobotController::class, 'setSpeed'])->name('robot.speed');
    Route::get('/key', [RobotController::class, 'getApiKey'])->name('robot.key');
    Route::post('/generate-key', [RobotController::class, 'generateApiKey'])->name('robot.generate-key');
});

Route::post('/upload-image', [ImageController::class, 'uploadImage'])->name('upload.image');

// Basic pages - order matters, put these before the dynamic username route
Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/about', [LandingPageController::class, 'index'])->name('about');
Route::get('/contact', function () { return view('contact');})->name('contact');
Route::get('/price', function () { return view('prices');})->name('prices');
Route::get('/faq', function () { return view('faqs');})->name('faq');
Route::get('/terms', function () { return view('terms');})->name('terms');
Route::get('/privacy-policy', function () { return view('privacy-policy');})->name('privacy-policy');
Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/tools/robot-control', function () { return view('plugins.robotControl');})->name('plugins.robotControl');
Route::get('/find-users', function () { return view('plugins.search_user');})->name('plugins.search-users');
Route::get('/documentation', function(){ return view('plugins.documentation');})->name('documentation');
Route::get('/settings', function() {return view('settings');})->name('settings');
Route::get('/forum', function(){ return view('plugins.forum');})->name('forum');

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// API routes
Route::get('/api/search-users', [ProfileController::class, 'search'])->name('api.users.search');
Route::get('/api/all-users', [ProfileController::class, 'getAllUsers'])->name('api.users.all');

// Profile route - should be last as it's a catch-all route
Route::get('/{username}', [ProfileController::class, 'show'])
    ->name('profile.show')
    ->where('username', '^(?!.*[0-9]+$)(?!dashboard$|slug$|about$|contact$|price$|faq$|terms$|privacy-policy$|news$|documentation$|documentation-esp32$|documentation-esp8266$|plugins$|find-users$|courses$|profile$|admin$|manage$|email$|courses-index$|forum$|settings$).*$');