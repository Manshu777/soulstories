@extends('admin.layouts.app')
@section('title', 'Settings')
@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-3xl rounded-xl border border-[#E5E7EB] bg-white p-5 space-y-4">
    @csrf
    @method('PATCH')
    <div>
        <label class="text-xs uppercase text-[#6B7280]">Platform Name</label>
        <input name="platform_name" value="{{ $settings['platform_name'] ?? '' }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-xs uppercase text-[#6B7280]">SEO Title</label>
        <input name="seo_title" value="{{ $settings['seo_title'] ?? '' }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-xs uppercase text-[#6B7280]">SEO Description</label>
        <textarea name="seo_description" rows="3" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">{{ $settings['seo_description'] ?? '' }}</textarea>
    </div>
    <div>
        <label class="text-xs uppercase text-[#6B7280]">Banner Text</label>
        <input name="banner_text" value="{{ $settings['banner_text'] ?? '' }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
    </div>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="feature_comments_enabled" value="1" @checked(($settings['feature_comments_enabled'] ?? '0') == '1')> Enable comments</label>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="feature_promotions_enabled" value="1" @checked(($settings['feature_promotions_enabled'] ?? '0') == '1')> Enable promotions</label>
    <button class="rounded-lg bg-[#6366F1] text-white px-4 py-2 text-sm">Save Settings</button>
</form>
@endsection
