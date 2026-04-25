<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login')->middleware('guest:admin');
    Route::post('/login', [AdminAuthController::class, 'login']);
});

Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');

    Route::get('/content/stories', [ContentController::class, 'stories'])->name('content.stories');
    Route::get('/content/stories/{story}', [ContentController::class, 'showStory'])->name('content.stories.show');
    Route::get('/content/blogs', [ContentController::class, 'blogs'])->name('content.blogs');
    Route::post('/content/stories/{story}/approve', [ContentController::class, 'approveStory'])->name('content.stories.approve');
    Route::post('/content/stories/{story}/reject', [ContentController::class, 'rejectStory'])->name('content.stories.reject');
    Route::get('/content/comments', [ContentController::class, 'comments'])->name('content.comments');
    Route::patch('/content/comments/{comment}', [ContentController::class, 'moderateComment'])->name('content.comments.moderate');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::patch('/roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.permissions');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');
    Route::post('/notifications/templates', [NotificationController::class, 'storeTemplate'])->name('notifications.templates.store');

    Route::get('/promotions', [AdminManagementController::class, 'promotions'])->name('promotions.index');
    Route::post('/promotions/packages', [AdminManagementController::class, 'storePackage'])->name('promotions.packages.store');

    Route::get('/payments', [AdminManagementController::class, 'payments'])->name('payments.index');
    Route::get('/payments/export', [AdminManagementController::class, 'exportPayments'])->name('payments.export');

    Route::get('/reports', [AdminManagementController::class, 'reports'])->name('reports.index');
    Route::patch('/reports/{report}', [AdminManagementController::class, 'updateReport'])->name('reports.update');

    Route::get('/read-earn', [AdminManagementController::class, 'readAndEarn'])->name('read-earn.index');
    Route::post('/read-earn/tasks', [AdminManagementController::class, 'storeTask'])->name('read-earn.tasks.store');
    Route::patch('/read-earn/submissions/{submission}', [AdminManagementController::class, 'moderateSubmission'])->name('read-earn.submissions.update');

    Route::get('/settings', [AdminManagementController::class, 'settings'])->name('settings.index');
    Route::patch('/settings', [AdminManagementController::class, 'updateSettings'])->name('settings.update');

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});
