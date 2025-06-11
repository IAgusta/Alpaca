<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleContentController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

/* 
Routes that Admin only ( Owner, Admin, Trainer ) can accesses
As Course management or User Management
*/
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