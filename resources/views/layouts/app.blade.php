<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Soul Diary') }}</title>

       <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
       <style>
        body { font-family: 'Inter', sans-serif; }
        .serif { font-family: 'Playfair Display', serif; }
        .bg-soul-gradient {
            background: radial-gradient(circle at top right, #fff7ed, #ffffff 50%);
        }
    </style>
    <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet">
<script src="https://unpkg.com/cropperjs/dist/cropper.js"></script>
</head>

<body class="bg-[#e6eaf6] text-slate-800 antialiased">

    {{-- Navbar --}}
  @if(!isset($hideNavbar))
        <x-navbar />
    @endif
    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @if(!isset($hideFooter))
    <footer class="bg-white mt-12 sm:mt-20 border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5 sm:py-6 text-center text-sm text-slate-500">
            © {{ date('Y') }} SoulDiary. All rights reserved.
        </div>
    </footer>
    @endif

</body>
</html>
