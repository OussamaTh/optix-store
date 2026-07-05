<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — {{ config('app.name', 'Optio') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18] antialiased font-sans">

    <div class="flex min-h-screen">

        <!-- Mobile overlay (click to close sidebar) -->
        <div id="sidebar-overlay"
            class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"
            aria-hidden="true"></div>

        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-64 shrink-0 bg-white border-r border-[#19140010] flex flex-col fixed inset-y-0 left-0 z-40 transform -translate-x-full transition-transform duration-200 ease-in-out lg:translate-x-0">
            <div class="h-20 flex items-center gap-2 px-6 border-b border-[#19140010]">
                <svg class="w-7 h-7 text-[#1b1b18]" viewBox="0 0 100 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="25" r="20" stroke="currentColor" stroke-width="8" fill="none" />
                    <circle cx="70" cy="25" r="20" stroke="currentColor" stroke-width="8" fill="none" />
                    <path d="M48 25H52" stroke="currentColor" stroke-width="8" stroke-linecap="round" />
                </svg>
                <div class="leading-tight">
                    <p class="text-[10px] uppercase tracking-wider text-gray-400 font-medium">Admin</p>
                </div>

                <button type="button" id="sidebar-close"
                    class="ml-auto lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-[#1b1b18] hover:bg-gray-50 transition-colors"
                    aria-label="Close sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white' : 'text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.products.*') ? 'bg-black text-white' : 'text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.59 13.41L11 23l-9-9 9.59-9.59a2 2 0 011.41-.41H20a2 2 0 012 2v6.59a2 2 0 01-.41 1.41z" />
                        <circle cx="7.5" cy="7.5" r="1.5" fill="currentColor" stroke="none" />
                    </svg>
                    Products
                </a>
                <a href="{{ route('admin.variants.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.variants.*') ? 'bg-black text-white' : 'text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Variants
                </a>
                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-black text-white' : 'text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Orders
                </a>
                <a href="{{ route('admin.customers.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.customers.*') ? 'bg-black text-white' : 'text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 014-3.87m1-5.13a4 4 0 110-8 4 4 0 010 8zm6 0a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                    Customers
                </a>

                <hr class="border-gray-100 my-4" />

                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-black text-white' : 'text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <circle cx="12" cy="12" r="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Settings
                </a>

                <a href="{{ url('/') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to store
                </a>
            </nav>

            <div class="p-4 border-t border-[#19140010]">
                <div class="flex items-center gap-3 px-2 py-2">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-[#1b1b18] shrink-0">
                        {{ Str::upper(Str::substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="leading-tight min-w-0">
                        <p class="text-xs font-semibold text-[#1b1b18] truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}" class="mt-1">
                    @csrf
                    <button type="submit"
                        class="w-full text-left flex items-center gap-3 px-2 py-2 rounded-xl text-xs font-semibold text-red-500 hover:bg-red-50/50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 lg:ml-64 min-w-0">
            <header class="h-20 bg-[#FDFDFC]/80 backdrop-blur-md sticky top-0 z-20 border-b border-[#19140010] flex items-center gap-3 px-4 sm:px-8">
                <button type="button" id="sidebar-open"
                    class="lg:hidden p-2 -ml-2 rounded-xl text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50 transition-colors"
                    aria-label="Open sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-xl font-semibold text-[#1b1b18] truncate">@yield('page-title', 'Dashboard')</h1>
            </header>

            <main class="px-4 sm:px-8 py-8 max-w-7xl">
                @if (session('success'))
                    <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-start gap-3 shadow-sm" id="success-alert">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-medium text-sm">{{ session('success') }}</p>
                        <button onclick="document.getElementById('success-alert').remove()" class="ml-auto text-emerald-500 hover:text-emerald-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm" id="error-alert">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="font-medium text-sm">Please correct the errors below:</p>
                                <ul class="mt-1 list-disc list-inside text-xs text-rose-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (function () {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openBtn = document.getElementById('sidebar-open');
            const closeBtn = document.getElementById('sidebar-close');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            openBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);

            // Close on nav link click (mobile only — sidebar is already
            // permanently visible at lg breakpoint, so this is a no-op there).
            sidebar?.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });

            // Close on Escape.
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeSidebar();
            });

            // If the viewport is resized up to desktop while the mobile
            // sidebar happens to be open, make sure the overlay/scroll-lock
            // don't linger.
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>