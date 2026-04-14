<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
class ProfileController extends Controller
{

    /* ================= PROFILE EDIT ================= */

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /* ================= PROFILE UPDATE ================= */

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /* ================= DELETE ACCOUNT ================= */

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /* ================= SELECT PRESET AVATAR ================= */

    public function selectAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|string'
        ]);

        $user = auth()->user();

        $user->avatar = $request->avatar;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Avatar updated successfully'
        ]);
    }

    /* ================= UPLOAD CUSTOM AVATAR ================= */

    public function uploadAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

          Log::info('Avatar upload request received');

                $user = auth()->user();

                Log::info('Authenticated user:', [
                    'user' => $user
                ]);


        $path = $request->file('avatar')->store('avatars', 'public');

        $user->avatar = 'storage/' . $path;
        $user->save();

        return back()->with('success', 'Avatar updated successfully');
    }
}