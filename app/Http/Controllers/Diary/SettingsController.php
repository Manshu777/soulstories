<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $activeTab = $request->string('tab', 'account')->toString();

        $preferences = $user->preference ?: new UserPreference([
            'notifications' => $this->defaultNotifications(),
            'preferred_genres' => [],
            'language' => 'hinglish',
            'mature_content' => false,
        ]);

        return view('diary.settings.index', [
            'user' => $user,
            'activeTab' => $activeTab,
            'preferences' => $preferences,
            'mutedUsers' => $user->mutedUsers()->select('users.id', 'users.name', 'users.username', 'users.avatar')->get(),
            'blockedUsers' => $user->blockedUsers()->select('users.id', 'users.name', 'users.username', 'users.avatar')->get(),
            'genreOptions' => ['Romance', 'Horror', 'Dark', 'College', 'Thriller', 'Short Stories', 'Motivation', 'Life'],
        ]);
    }

    public function updateAccount(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'beta_program' => ['nullable', 'boolean'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && str_starts_with($user->profile_image, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->profile_image));
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = 'storage/' . $path;
            $user->avatar = $user->profile_image;
        }

        $user->beta_program = (bool) ($data['beta_program'] ?? false);

        if (! empty($data['new_password'])) {
            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        return back()->with('success', 'Account settings updated.');
    }

    public function updateNotifications(Request $request)
    {
        $user = $request->user();

        $flags = $request->validate([
            'new_followers' => ['nullable', 'boolean'],
            'story_comments' => ['nullable', 'boolean'],
            'story_likes' => ['nullable', 'boolean'],
            'author_releases' => ['nullable', 'boolean'],
            'promotions' => ['nullable', 'boolean'],
        ]);

        $preference = $user->preference()->firstOrCreate(['user_id' => $user->id], [
            'notifications' => $this->defaultNotifications(),
            'preferred_genres' => [],
            'language' => 'hinglish',
            'mature_content' => false,
        ]);

        $preference->notifications = [
            'new_followers' => (bool) ($flags['new_followers'] ?? false),
            'story_comments' => (bool) ($flags['story_comments'] ?? false),
            'story_likes' => (bool) ($flags['story_likes'] ?? false),
            'author_releases' => (bool) ($flags['author_releases'] ?? false),
            'promotions' => (bool) ($flags['promotions'] ?? false),
        ];
        $preference->save();

        return back()->with('success', 'Notification settings updated.');
    }

    public function updatePreferences(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'preferred_genres' => ['nullable', 'array'],
            'preferred_genres.*' => ['string', 'max:60'],
            'language' => ['required', 'in:hindi,hinglish'],
            'mature_content' => ['nullable', 'boolean'],
        ]);

        $preference = $user->preference()->firstOrCreate(['user_id' => $user->id], [
            'notifications' => $this->defaultNotifications(),
            'preferred_genres' => [],
            'language' => 'hinglish',
            'mature_content' => false,
        ]);

        $preference->preferred_genres = array_values($data['preferred_genres'] ?? []);
        $preference->language = $data['language'];
        $preference->mature_content = (bool) ($data['mature_content'] ?? false);
        $preference->save();

        return back()->with('success', 'Content preferences updated.');
    }

    public function unmute(Request $request, User $user)
    {
        $request->user()->mutedUsers()->detach($user->id);
        return back()->with('success', 'Account unmuted.');
    }

    public function unblock(Request $request, User $user)
    {
        $request->user()->blockedUsers()->detach($user->id);
        return back()->with('success', 'Account unblocked.');
    }

    public function downloadData(Request $request, string $format)
    {
        $user = $request->user()->load('preference');

        $payload = [
            'account' => [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'dob' => $user->dob,
                'beta_program' => (bool) $user->beta_program,
                'created_at' => $user->created_at,
            ],
            'preference' => $user->preference,
        ];

        if ($format === 'json') {
            return response()->json($payload)
                ->header('Content-Disposition', 'attachment; filename="soul-diaries-data.json"');
        }

        if ($format === 'html') {
            $html = '<h1>Soul Diaries Personal Data</h1><pre>' . e(json_encode($payload, JSON_PRETTY_PRINT)) . '</pre>';
            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'attachment; filename="soul-diaries-data.html"');
        }

        abort(404);
    }

    private function defaultNotifications(): array
    {
        return [
            'new_followers' => true,
            'story_comments' => true,
            'story_likes' => true,
            'author_releases' => true,
            'promotions' => false,
        ];
    }
}
