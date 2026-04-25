<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EarnSubmission;
use App\Models\EarnTask;
use App\Models\PlatformSetting;
use App\Models\Promotion;
use App\Models\PromotionPackage;
use App\Models\ServicePayment;
use App\Models\StoryReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminManagementController extends Controller
{
    public function promotions()
    {
        $packages = PromotionPackage::latest()->paginate(10);
        $promotions = Promotion::with(['user:id,name,email', 'package:id,name,price'])
            ->latest()
            ->paginate(15);

        return view('admin.promotions.index', compact('packages', 'promotions'));
    }

    public function storePackage(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'in:book,brand'],
            'title' => ['required', 'string', 'max:120'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'features' => ['nullable', 'string', 'max:1000'],
        ]);
        PromotionPackage::create([
            ...$data,
            'features' => ! empty($data['features'])
                ? array_values(array_filter(array_map('trim', explode(',', $data['features']))))
                : [],
        ]);
        return back()->with('success', 'Promotion package created.');
    }

    public function payments()
    {
        $servicePayments = ServicePayment::latest()->paginate(20);
        $promotionPayments = DB::table('promotion_payments')->latest()->paginate(20, ['*'], 'promotion_page');
        return view('admin.payments.index', compact('servicePayments', 'promotionPayments'));
    }

    public function exportPayments(): StreamedResponse
    {
        $payments = ServicePayment::query()->latest()->limit(5000)->get(['payment_id', 'amount', 'status', 'created_at']);

        return response()->streamDownload(function () use ($payments) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['payment_id', 'amount', 'status', 'created_at']);
            foreach ($payments as $payment) {
                fputcsv($out, [$payment->payment_id, $payment->amount, $payment->status, (string) $payment->created_at]);
            }
            fclose($out);
        }, 'service-payments.csv', ['Content-Type' => 'text/csv']);
    }

    public function reports()
    {
        $reports = StoryReport::with(['user:id,name,email', 'story:id,title,slug'])->latest()->paginate(20);
        return view('admin.reports.index', compact('reports'));
    }

    public function updateReport(Request $request, StoryReport $report)
    {
        $data = $request->validate(['status' => ['required', 'in:pending,reviewed,dismissed']]);
        $report->update(['status' => $data['status']]);
        return back()->with('success', 'Report status updated.');
    }

    public function readAndEarn()
    {
        $tasks = EarnTask::latest()->paginate(10);
        $submissions = EarnSubmission::with(['order.user:id,name,email', 'task:id,title,reward_amount'])
            ->latest()
            ->paginate(15);
        return view('admin.read-earn.index', compact('tasks', 'submissions'));
    }

    public function storeTask(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'reward_amount' => ['required', 'numeric', 'min:0'],
            'platform' => ['required', 'string', 'max:60'],
        ]);
        EarnTask::create($data);
        return back()->with('success', 'Task created.');
    }

    public function moderateSubmission(Request $request, EarnSubmission $submission)
    {
        $data = $request->validate(['status' => ['required', 'in:pending,approved,rejected']]);
        $submission->update(['status' => $data['status']]);
        return back()->with('success', 'Submission updated.');
    }

    public function settings()
    {
        $settings = PlatformSetting::query()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'platform_name' => ['nullable', 'string', 'max:100'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'banner_text' => ['nullable', 'string', 'max:500'],
            'feature_comments_enabled' => ['nullable', 'boolean'],
            'feature_promotions_enabled' => ['nullable', 'boolean'],
        ]);
        $data['feature_comments_enabled'] = $request->boolean('feature_comments_enabled');
        $data['feature_promotions_enabled'] = $request->boolean('feature_promotions_enabled');

        foreach ($data as $key => $value) {
            PlatformSetting::updateOrCreate(['key' => $key], ['value' => is_bool($value) ? (int) $value : (string) $value]);
        }

        return back()->with('success', 'Settings updated.');
    }
}
