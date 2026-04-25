<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendAdminBroadcastJob;
use App\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $templates = NotificationTemplate::latest()->paginate(15);
        $users = User::latest()->limit(50)->get(['id', 'name', 'email']);

        return view('admin.notifications.index', compact('templates', 'users'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'audience' => ['required', 'in:all,writers,selected'],
            'subject' => ['required', 'string', 'max:180'],
            'body' => ['required', 'string'],
            'user_ids' => ['array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        dispatch(new SendAdminBroadcastJob(
            audience: $data['audience'],
            subjectLine: $data['subject'],
            bodyHtml: $data['body'],
            userIds: $data['user_ids'] ?? [],
        ));

        return back()->with('success', 'Broadcast queued successfully.');
    }

    public function storeTemplate(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:120', 'unique:notification_templates,slug'],
            'subject' => ['required', 'string', 'max:180'],
            'body' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        NotificationTemplate::create([
            ...$data,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return back()->with('success', 'Template created.');
    }
}
