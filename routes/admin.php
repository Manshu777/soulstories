<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminStoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
    Route::post('/login', [AdminAuthController::class, 'login']);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminStoryController::class, 'dashboard'])->name('dashboard');
    Route::get('/stories', [AdminStoryController::class, 'index'])->name('stories.index');
    Route::post('/stories/{story}/approve', [AdminStoryController::class, 'approve'])->name('stories.approve');
    Route::post('/stories/{story}/reject', [AdminStoryController::class, 'reject'])->name('stories.reject');
    Route::delete('/stories/{story}', [AdminStoryController::class, 'destroy'])->name('stories.destroy');

    Route::post('/users/{user}/block', [AdminStoryController::class, 'blockUser'])->name('users.block');
    Route::post('/users/{user}/unblock', [AdminStoryController::class, 'unblockUser'])->name('users.unblock');

    Route::get('/comments', [AdminStoryController::class, 'moderateComments'])->name('comments.index');
    Route::post('/comments/{comment}/hide', [AdminStoryController::class, 'hideComment'])->name('comments.hide');
    Route::post('/comments/{comment}/remove', [AdminStoryController::class, 'removeComment'])->name('comments.remove');
    Route::post('/comments/{comment}/show', [AdminStoryController::class, 'showComment'])->name('comments.show');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});
