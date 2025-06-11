<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCourseController;

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    // User course routes
    Route::get('/courses', [UserCourseController::class, 'index'])->name('user.course');
    Route::get('/courses/feed', [UserCourseController::class, 'feed'])->name('course.feed');
    Route::get('/courses/section', [UserCourseController::class, 'section'])
        ->name('courses.section');
    Route::get('/courses/library', [UserCourseController::class, 'userFeed'])->name('user.course.library');
    Route::post('/courses/add/{courseId}', [UserCourseController::class, 'add'])->name('user.course.add');
    
    Route::get('/{slug}/{courseId}', [UserCourseController::class, 'detail'])
        ->name('user.course.detail')
        ->where(['courseId' => '[0-9]+', 'slug' => '[a-zA-Z0-9-]+']);
    Route::get('/{slug}/{courseId}/chapter/{moduleTitle}/{moduleId}', [UserCourseController::class, 'open'])
        ->name('course.module.open')
        ->where(['courseId' => '[0-9]+', 'moduleId' => '[0-9]+', 'slug' => '[a-zA-Z0-9-]+']);

    Route::post('/module-progress/{moduleId}/toggle', [UserCourseController::class, 'toggle'])->name('module.progress.toggle');
    Route::post('/courses/{courseId}/toggle-all', [UserCourseController::class, 'toggleAllModules'])->name('user.course.toggleAll');
    Route::post('/courses/clear-history/{courseId}', [UserCourseController::class, 'clearHistory'])->name('user.course.clearHistory');
    Route::delete('/courses/delete/{courseId}', [UserCourseController::class, 'delete'])->name('user.course.delete');
    Route::post('/exercise/submit', [UserCourseController::class, 'submitExercise'])->name('user.exercise.submit');
    Route::post('/mark-module-as-read/{moduleId}', [UserCourseController::class, 'markModuleAsRead']);
    Route::get('/get-drawer-content/{moduleId}', [UserCourseController::class, 'getDrawerContent']);
});