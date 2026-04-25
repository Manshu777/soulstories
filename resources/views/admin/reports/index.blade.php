@extends('admin.layouts.app')
@section('title', 'Reporting System')
@section('content')
<div class="rounded-xl border border-[#E5E7EB] bg-white overflow-auto">
    <table class="w-full text-sm">
        <thead class="bg-[#F9FAFB] text-left text-[#6B7280]"><tr><th class="p-3">Reporter</th><th class="p-3">Story</th><th class="p-3">Reason</th><th class="p-3">Status</th></tr></thead>
        <tbody>
        @foreach($reports as $report)
            <tr class="border-t">
                <td class="p-3">{{ $report->user->name ?? '-' }}</td>
                <td class="p-3">{{ $report->story->title ?? '-' }}</td>
                <td class="p-3">{{ $report->reason }}</td>
                <td class="p-3">
                    <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="flex gap-2">@csrf @method('PATCH')
                        <select name="status" class="border rounded px-2 py-1">
                        @foreach(['pending','reviewed','dismissed'] as $status)
                                <option value="{{ $status }}" @selected($report->status===$status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        <button class="px-2 py-1 border rounded">Save</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $reports->links() }}</div>
@endsection
