<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminAnalyticsService;

class AdminDashboardController extends Controller
{
    public function __construct(private readonly AdminAnalyticsService $analyticsService)
    {
    }

    public function index()
    {
        $metrics = $this->analyticsService->dashboardMetrics();

        return view('admin.dashboard', compact('metrics'));
    }
}
