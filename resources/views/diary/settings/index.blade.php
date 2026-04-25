@extends('layouts.app')

@section('content')
@php
    $tab = in_array($activeTab, ['account','notifications','preferences','muted','blocked'], true) ? $activeTab : 'account';
    $dob = $user->dob ? \Carbon\Carbon::parse($user->dob) : null;
    $notifications = $preferences->notifications ?? [];
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8" x-data="{ saving:false }">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-semibold text-[#111827]">Settings</h1>
        <p class="text-sm text-[#6B7280] mt-1">Manage your account and preferences.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-[#E5E7EB] bg-[#EEF2FF] text-[#4F46E5] px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto pb-2 mb-6">
        <nav class="inline-flex min-w-full sm:min-w-0 border-b border-[#E5E7EB] gap-6">
            <a href="{{ route('diary.settings', ['tab' => 'account']) }}" class="py-3 text-sm font-semibold border-b-2 {{ $tab === 'account' ? 'border-[#6366F1] text-[#6366F1]' : 'border-transparent text-[#6B7280]' }}">Account</a>
            <a href="{{ route('diary.settings', ['tab' => 'notifications']) }}" class="py-3 text-sm font-semibold border-b-2 {{ $tab === 'notifications' ? 'border-[#6366F1] text-[#6366F1]' : 'border-transparent text-[#6B7280]' }}">Notifications</a>
            <a href="{{ route('diary.settings', ['tab' => 'preferences']) }}" class="py-3 text-sm font-semibold border-b-2 {{ $tab === 'preferences' ? 'border-[#6366F1] text-[#6366F1]' : 'border-transparent text-[#6B7280]' }}">Content Preferences</a>
            <a href="{{ route('diary.settings', ['tab' => 'muted']) }}" class="py-3 text-sm font-semibold border-b-2 {{ $tab === 'muted' ? 'border-[#6366F1] text-[#6366F1]' : 'border-transparent text-[#6B7280]' }}">Muted Accounts</a>
            <a href="{{ route('diary.settings', ['tab' => 'blocked']) }}" class="py-3 text-sm font-semibold border-b-2 {{ $tab === 'blocked' ? 'border-[#6366F1] text-[#6366F1]' : 'border-transparent text-[#6B7280]' }}">Blocked Accounts</a>
        </nav>
    </div>

    @if($tab === 'account')
        <section class="rounded-2xl border border-[#E5E7EB] bg-white shadow-[0_8px_24px_-16px_rgba(17,24,39,0.25)] p-6">
            <form method="POST" action="{{ route('diary.settings.account') }}" enctype="multipart/form-data" class="space-y-5" @submit="saving=true">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">Username</label>
                        <input value="{{ $user->username }}" readonly class="mt-1 w-full rounded-xl border border-[#E5E7EB] bg-slate-50 px-3 py-2.5 text-sm text-[#6B7280] cursor-not-allowed">
                    </div>
                    <div>
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">Email</label>
                        <input type="email" value="{{ $user->email }}" readonly class="mt-1 w-full rounded-xl border border-[#E5E7EB] bg-slate-50 px-3 py-2.5 text-sm text-[#6B7280] cursor-not-allowed">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">Day</label>
                        <select disabled class="mt-1 w-full rounded-xl border border-[#E5E7EB] bg-slate-50 px-3 py-2.5 text-sm text-[#6B7280] cursor-not-allowed">
                            <option value="">Day</option>
                            @for($d=1; $d<=31; $d++)
                                <option value="{{ $d }}" @selected((int) old('birth_day', $dob?->day) === $d)>{{ $d }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">Month</label>
                        <select disabled class="mt-1 w-full rounded-xl border border-[#E5E7EB] bg-slate-50 px-3 py-2.5 text-sm text-[#6B7280] cursor-not-allowed">
                            <option value="">Month</option>
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" @selected((int) old('birth_month', $dob?->month) === $m)>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">Year</label>
                        <select disabled class="mt-1 w-full rounded-xl border border-[#E5E7EB] bg-slate-50 px-3 py-2.5 text-sm text-[#6B7280] cursor-not-allowed">
                            <option value="">Year</option>
                            @for($y=now()->year; $y>=1900; $y--)
                                <option value="{{ $y }}" @selected((int) old('birth_year', $dob?->year) === $y)>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <p class="text-xs text-[#6B7280] -mt-2">Username, email, and birthday are locked and cannot be changed.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">Profile Image</label>
                        <input type="file" accept="image/*" name="profile_image" class="mt-1 block w-full text-sm text-[#6B7280]">
                        @error('profile_image') <p class="text-xs text-[#6366F1] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center gap-2 pt-6">
                        <input id="beta_program" type="checkbox" name="beta_program" value="1" @checked(old('beta_program', $user->beta_program)) class="rounded border-[#E5E7EB] text-[#6366F1] focus:ring-[#6366F1]/20">
                        <label for="beta_program" class="text-sm text-[#111827]">Join Beta Program</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-[#E5E7EB] pt-4">
                    <div>
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">Current Password <span class="text-[#6366F1] ml-1">Change password</span></label>
                        <input type="password" name="current_password" class="mt-1 w-full rounded-xl border border-[#E5E7EB] px-3 py-2.5 text-sm">
                        @error('current_password') <p class="text-xs text-[#6366F1] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">New Password</label>
                        <input type="password" name="new_password" class="mt-1 w-full rounded-xl border border-[#E5E7EB] px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="mt-1 w-full rounded-xl border border-[#E5E7EB] px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 items-center justify-between border-t border-[#E5E7EB] pt-4">
                    <div class="flex gap-2">
                        <a href="{{ route('diary.settings.download', 'html') }}" class="inline-flex items-center rounded-xl border border-[#6366F1] text-[#6366F1] px-4 py-2 text-sm font-semibold hover:bg-[#EEF2FF]">Download HTML</a>
                        <a href="{{ route('diary.settings.download', 'json') }}" class="inline-flex items-center rounded-xl border border-[#6366F1] text-[#6366F1] px-4 py-2 text-sm font-semibold hover:bg-[#EEF2FF]">Download JSON</a>
                    </div>
                    <button type="submit" :disabled="saving" class="inline-flex items-center rounded-xl bg-[#6366F1] text-white px-5 py-2.5 text-sm font-semibold border border-[#6366F1] hover:bg-[#4F46E5] disabled:opacity-50">
                        <span x-show="!saving">Save Account</span>
                        <span x-show="saving">Saving...</span>
                    </button>
                </div>
            </form>
        </section>
    @elseif($tab === 'notifications')
        <section class="rounded-2xl border border-[#E5E7EB] bg-white shadow-[0_8px_24px_-16px_rgba(17,24,39,0.25)] p-6">
            <form method="POST" action="{{ route('diary.settings.notifications') }}" class="space-y-5" @submit="saving=true">
                @csrf
                @php
                    $toggles = [
                        'new_followers' => 'New followers',
                        'story_comments' => 'Comments on stories',
                        'story_likes' => 'Likes on stories',
                        'author_releases' => 'New releases from followed authors',
                        'promotions' => 'Promotions & announcements',
                    ];
                @endphp
                @foreach($toggles as $key => $label)
                    <label class="flex items-center justify-between rounded-xl border border-[#E5E7EB] px-4 py-3">
                        <span class="text-sm text-[#111827]">{{ $label }}</span>
                        <input type="hidden" name="{{ $key }}" value="0">
                        <input type="checkbox" name="{{ $key }}" value="1" @checked(old($key, $notifications[$key] ?? false)) class="rounded border-[#E5E7EB] text-[#6366F1] focus:ring-[#6366F1]/20">
                    </label>
                @endforeach
                <button type="submit" :disabled="saving" class="inline-flex items-center rounded-xl bg-[#6366F1] text-white px-5 py-2.5 text-sm font-semibold border border-[#6366F1] hover:bg-[#4F46E5] disabled:opacity-50">Save Notifications</button>
            </form>
        </section>
    @elseif($tab === 'preferences')
        <section class="rounded-2xl border border-[#E5E7EB] bg-white shadow-[0_8px_24px_-16px_rgba(17,24,39,0.25)] p-6">
            <form method="POST" action="{{ route('diary.settings.preferences') }}" class="space-y-5" @submit="saving=true">
                @csrf

                <div>
                    <p class="text-xs uppercase font-semibold text-[#6B7280] mb-2">Preferred Genres</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($genreOptions as $genre)
                            <label class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-[#E5E7EB] text-sm">
                                <input type="checkbox" name="preferred_genres[]" value="{{ $genre }}" @checked(in_array($genre, old('preferred_genres', $preferences->preferred_genres ?? []), true)) class="rounded border-[#E5E7EB] text-[#6366F1]">
                                <span>{{ $genre }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">Language Preference</label>
                        <select name="language" class="mt-1 w-full rounded-xl border border-[#E5E7EB] px-3 py-2.5 text-sm">
                            <option value="hindi" @selected(old('language', $preferences->language) === 'hindi')>Hindi</option>
                            <option value="hinglish" @selected(old('language', $preferences->language) === 'hinglish')>Hinglish</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 pt-6">
                        <input id="mature_content" type="hidden" name="mature_content" value="0">
                        <input id="mature_content_toggle" type="checkbox" name="mature_content" value="1" @checked(old('mature_content', $preferences->mature_content)) class="rounded border-[#E5E7EB] text-[#6366F1]">
                        <label for="mature_content_toggle" class="text-sm text-[#111827]">Mature content (ON/OFF)</label>
                    </div>
                </div>

                <button type="submit" :disabled="saving" class="inline-flex items-center rounded-xl bg-[#6366F1] text-white px-5 py-2.5 text-sm font-semibold border border-[#6366F1] hover:bg-[#4F46E5] disabled:opacity-50">Save Preferences</button>
            </form>
        </section>
    @elseif($tab === 'muted')
        <section class="rounded-2xl border border-[#E5E7EB] bg-white shadow-[0_8px_24px_-16px_rgba(17,24,39,0.25)] p-6">
            <h2 class="text-lg font-semibold text-[#111827] mb-4">Muted Accounts</h2>
            <div class="space-y-3">
                @forelse($mutedUsers as $muted)
                    <div class="flex items-center justify-between border border-[#E5E7EB] rounded-xl px-4 py-3">
                        <div>
                            <p class="text-sm font-medium text-[#111827]">{{ $muted->name }}</p>
                            <p class="text-xs text-[#6B7280]">@{{ $muted->username }}</p>
                        </div>
                        <form method="POST" action="{{ route('diary.settings.unmute', $muted->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-xl border border-[#6366F1] text-[#6366F1] px-3 py-1.5 text-xs font-semibold hover:bg-[#EEF2FF]">Unmute</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-[#6B7280]">No muted accounts.</p>
                @endforelse
            </div>
        </section>
    @elseif($tab === 'blocked')
        <section class="rounded-2xl border border-[#E5E7EB] bg-white shadow-[0_8px_24px_-16px_rgba(17,24,39,0.25)] p-6">
            <h2 class="text-lg font-semibold text-[#111827] mb-4">Blocked Accounts</h2>
            <div class="space-y-3">
                @forelse($blockedUsers as $blocked)
                    <div class="flex items-center justify-between border border-[#E5E7EB] rounded-xl px-4 py-3">
                        <div>
                            <p class="text-sm font-medium text-[#111827]">{{ $blocked->name }}</p>
                            <p class="text-xs text-[#6B7280]">@{{ $blocked->username }}</p>
                        </div>
                        <form method="POST" action="{{ route('diary.settings.unblock', $blocked->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-xl border border-[#6366F1] text-[#6366F1] px-3 py-1.5 text-xs font-semibold hover:bg-[#EEF2FF]">Unblock</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-[#6B7280]">No blocked accounts.</p>
                @endforelse
            </div>
        </section>
    @endif
</div>
@endsection
