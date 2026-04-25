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
        :root {
            --sd-primary: #6366F1;
            --sd-primary-dark: #4F46E5;
            --sd-primary-light: #EEF2FF;
            --sd-bg: #FFFFFF;
            --sd-text: #111827;
            --sd-text-muted: #6B7280;
            --sd-border: #E5E7EB;
        }
        body { font-family: 'Inter', sans-serif; background: var(--sd-bg); color: var(--sd-text); }
        .serif { font-family: 'Playfair Display', serif; }
        .sd-text-primary { color: var(--sd-primary); }
        .sd-bg-primary { background-color: var(--sd-primary); }
        .sd-bg-primary-light { background-color: var(--sd-primary-light); }
        .sd-border { border-color: var(--sd-border); }
        .sd-shadow { box-shadow: 0 8px 24px -16px rgba(17, 24, 39, 0.25); }
    </style>
    <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet">
<script src="https://unpkg.com/cropperjs/dist/cropper.js"></script>
</head>

<body class="bg-white text-[#111827] antialiased">

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
    <footer class="bg-white mt-12 sm:mt-20 border-t border-[#E5E7EB]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5 sm:py-6 text-center text-sm text-[#6B7280]">
            © {{ date('Y') }} SoulDiary. All rights reserved.
        </div>
    </footer>
    @endif

</body>
</html>
