<?php

use App\Http\Controllers\aiController;
use App\Http\Controllers\Diary\AuthorController;
use App\Http\Controllers\Diary\ChapterController;
use App\Http\Controllers\Diary\CategoryController;
use App\Http\Controllers\Diary\CommentController;
use App\Http\Controllers\Diary\DashboardController;
use App\Http\Controllers\Diary\SettingsController;
use App\Http\Controllers\Diary\LibraryController;
use App\Http\Controllers\Diary\LikeController;
use App\Http\Controllers\Diary\ReportController;
use App\Http\Controllers\Diary\SearchController;
use App\Http\Controllers\Diary\StoryReviewController;
use App\Http\Controllers\Diary\StoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoryController::class, 'index'])->name('diary.home');

Route::get('/stories/create', [StoryController::class, 'create'])
    ->name('diary.stories.create')
    ->middleware('auth');

Route::get('/stories/{slug}', [StoryController::class, 'show'])->name('diary.stories.show');
Route::get('/story/{slug}', [StoryController::class, 'show'])->name('diary.story.show');
Route::get('/author/{username}', [AuthorController::class, 'show'])->name('diary.authors.show');
Route::get('/search', [SearchController::class, 'index'])->name('diary.search');
Route::get('/categories', [CategoryController::class, 'index'])->name('diary.categories.index');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('diary.category.show');

Route::get('/chapter-writer', fn () => view('diary.chapter-writer'))
    ->middleware('auth')
    ->name('diary.chapter-writer');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('diary.dashboard');

    Route::post('/profile/avatar/select', [ProfileController::class, 'selectAvatar'])->name('profile.avatar.select');
    Route::post('/profile/avatar/upload', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');

    Route::post('/stories', [StoryController::class, 'store'])->name('diary.stories.store');
    Route::get('/stories/{story}/edit', [StoryController::class, 'edit'])->name('diary.stories.edit');
    Route::put('/stories/{story}', [StoryController::class, 'update'])->name('diary.stories.update');
    Route::delete('/stories/{story}', [StoryController::class, 'destroy'])->name('diary.stories.destroy');

    // requested route style: /stories/{id}/chapters/create
    Route::get('/stories/{id}/chapters/create', [ChapterController::class, 'createById'])
        ->whereNumber('id')
        ->name('diary.chapters.create.byId');

    Route::get('/stories/{story}/chapters/create', [ChapterController::class, 'create'])->name('diary.chapters.create');
    Route::post('/stories/{story}/chapters', [ChapterController::class, 'store'])->name('diary.chapters.store');
    Route::post('/stories/{story}/chapters/autosave', [ChapterController::class, 'autosave'])->name('diary.chapters.autosave');
    Route::post('/stories/{story}/chapters/upload-image', [ChapterController::class, 'uploadEditorImage'])->name('diary.chapters.upload-image');
    Route::post('/stories/{story}/chapters/upload-audio', [ChapterController::class, 'uploadEditorAudio'])->name('diary.chapters.upload-audio');

    Route::get('/stories/{story}/chapters/{chapter}/edit', [ChapterController::class, 'edit'])->name('diary.chapters.edit');
    Route::put('/stories/{story}/chapters/{chapter}', [ChapterController::class, 'update'])->name('diary.chapters.update');
    Route::delete('/stories/{story}/chapters/{chapter}', [ChapterController::class, 'destroy'])->name('diary.chapters.destroy');
    Route::post('/stories/{story}/chapters/reorder', [ChapterController::class, 'reorder'])->name('diary.chapters.reorder');

    Route::get('/stories/{story}/chapters/{chapter}', [ChapterController::class, 'show'])->name('diary.chapters.show');

    Route::post('/stories/{story}/like', [LikeController::class, 'story'])->name('diary.like.story');
    Route::post('/stories/{story}/chapters/{chapter}/like', [LikeController::class, 'chapter'])->name('diary.like.chapter');

    Route::post('/comments', [CommentController::class, 'store'])->name('diary.comments.store');

    Route::get('/library', [LibraryController::class, 'index'])->name('diary.library.index');
    Route::post('/library/{story}', [LibraryController::class, 'store'])->name('diary.library.store');
    Route::delete('/library/{story}', [LibraryController::class, 'destroy'])->name('diary.library.destroy');

    Route::post('/stories/{story}/report', [ReportController::class, 'store'])->name('diary.report.store');
    Route::post('/story/{story}/review', [StoryReviewController::class, 'store'])->name('diary.story.review');

    Route::post('/author/{author}/follow', [AuthorController::class, 'follow'])->name('diary.authors.follow');
    Route::post('/author/{author}/unfollow', [AuthorController::class, 'unfollow'])->name('diary.authors.unfollow');
    Route::post('/follow/{author}', [AuthorController::class, 'follow'])->name('diary.follow');
    Route::delete('/unfollow/{author}', [AuthorController::class, 'unfollow'])->name('diary.unfollow');

    Route::get('/settings', [SettingsController::class, 'index'])->name('diary.settings');
    Route::post('/settings/account', [SettingsController::class, 'updateAccount'])->name('diary.settings.account');
    Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('diary.settings.notifications');
    Route::post('/settings/preferences', [SettingsController::class, 'updatePreferences'])->name('diary.settings.preferences');
    Route::delete('/settings/muted/{user}', [SettingsController::class, 'unmute'])->name('diary.settings.unmute');
    Route::delete('/settings/blocked/{user}', [SettingsController::class, 'unblock'])->name('diary.settings.unblock');
    Route::get('/settings/download/{format}', [SettingsController::class, 'downloadData'])->name('diary.settings.download');

    Route::get("/ai/image",[aiController::class,"showAi"]);
  Route::post("/ai/generate-image",[aiController::class,"generate"])->name('generate.image');;
     

});
