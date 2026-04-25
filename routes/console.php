<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    DB::table('stories')
        ->where('approval_status', 'pending')
        ->whereNull('admin_notes')
        ->where('created_at', '<=', now()->subDays(2))
        ->update(['approval_status' => 'approved', 'approved_at' => now()]);
})->hourly()->name('automation:auto-approve-low-risk-content');

Schedule::call(function () {
    $activeUsers = DB::table('users')->where('status', 'active')->count();
    $newStories = DB::table('stories')->whereDate('created_at', '>=', now()->subWeek())->count();
    Log::info('Weekly admin summary', ['active_users' => $activeUsers, 'new_stories' => $newStories]);
})->weeklyOn(1, '08:00')->name('automation:weekly-admin-report');

Schedule::call(function () {
    $inactiveUsers = DB::table('users')
        ->where('status', 'active')
        ->where('updated_at', '<', now()->subDays(30))
        ->whereNotNull('email')
        ->limit(200)
        ->get(['email', 'name']);

    foreach ($inactiveUsers as $user) {
        Mail::raw("Hi {$user->name}, we miss you on Soul Diaries. Come back and continue writing.", function ($message) use ($user) {
            $message->to($user->email)->subject('We miss you on Soul Diaries');
        });
    }
})->dailyAt('10:00')->name('automation:notify-inactive-users');

Schedule::call(function () {
    DB::table('promotions')
        ->where('status', 'active')
        ->whereNotNull('ends_at')
        ->where('ends_at', '<', now())
        ->update(['status' => 'expired']);
})->everyFifteenMinutes()->name('automation:expire-promotions');
