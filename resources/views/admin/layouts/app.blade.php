<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Soul Diaries</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            500: '#6366F1',
                            600: '#4F46E5',
                        },
                    },
                },
            },
        };
    </script>
    <style>
        body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
        @media (min-width: 1024px) {
            #admin-main { padding-left: 260px; transition: padding-left .25s ease; }
            body.sidebar-collapsed #admin-main { padding-left: 84px; }
            body.sidebar-collapsed #admin-sidebar { width: 84px; }
            body.sidebar-collapsed #admin-sidebar .menu-brand { display: none; }
            body.sidebar-collapsed #admin-sidebar .menu-label { display: none; }
            body.sidebar-collapsed #admin-sidebar nav a { justify-content: center; }
            body.sidebar-collapsed #admin-sidebar nav a span:first-child { margin-right: 0; }
        }
    </style>
</head>
<body class="bg-white text-[#111827]">
<div id="admin-sidebar-overlay" class="fixed inset-0 bg-[#111827]/30 z-30 hidden lg:hidden"></div>
@include('admin.components.sidebar')
<main id="admin-main" class="min-h-screen">
    @include('admin.components.navbar')
    <div class="p-4 sm:p-6 lg:p-8">
            @if(session('success'))
                <div class="mb-6 rounded-lg border border-[#C7D2FE] bg-[#EEF2FF] text-[#3730A3] px-4 py-3 text-sm font-medium">{{ session('success') }}</div>
            @endif
            @yield('content')
    </div>
</main>
</div>
<script>
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('admin-sidebar-overlay');
    const toggle = document.getElementById('admin-sidebar-toggle');
    const collapseToggle = document.getElementById('admin-sidebar-collapse');
    const collapseKey = 'admin.sidebar.collapsed';

    function openSidebar() {
        sidebar?.classList.remove('-translate-x-full');
        overlay?.classList.remove('hidden');
    }

    function closeSidebar() {
        sidebar?.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
    }

    toggle?.addEventListener('click', () => {
        if (sidebar?.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    });

    function applyCollapsedState() {
        if (window.innerWidth >= 1024 && localStorage.getItem(collapseKey) === '1') {
            document.body.classList.add('sidebar-collapsed');
        } else {
            document.body.classList.remove('sidebar-collapsed');
        }
    }

    collapseToggle?.addEventListener('click', () => {
        const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem(collapseKey, isCollapsed ? '1' : '0');
    });

    overlay?.addEventListener('click', closeSidebar);
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            overlay?.classList.add('hidden');
            sidebar?.classList.remove('-translate-x-full');
        } else if (!sidebar?.classList.contains('-translate-x-full')) {
            openSidebar();
        }
        applyCollapsedState();
    });
    applyCollapsedState();
</script>
@stack('scripts')
</body>
</html>
