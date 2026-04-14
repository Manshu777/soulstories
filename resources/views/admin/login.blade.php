<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – Soul Diaries</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-slate-200 p-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-1">Admin</h1>
        <p class="text-slate-500 text-sm mb-6">Soul Diaries</p>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="post" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-orange-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-orange-200">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="rounded border-slate-300">
                <label for="remember" class="ml-2 text-sm text-slate-600">Remember me</label>
            </div>
            <button type="submit" class="w-full py-2.5 bg-slate-800 text-white rounded-lg font-medium hover:bg-slate-700">
                Log in
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            <a href="{{ route('diary.home') }}" class="text-orange-500 hover:underline">← Back to site</a>
        </p>
    </div>
</body>
</html>
