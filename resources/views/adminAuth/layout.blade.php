<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — {{ config('app.name', 'Optio') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #0a0a0a;
            --paper: #ffffff;
            --hairline: #e5e5e5;
            --muted: #6b6b6b;
        }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
            color: var(--ink);
            background: var(--paper);
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased">

    <header class="border-b border-[var(--hairline)]">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2" aria-label="Home">
                <svg width="34" height="20" viewBox="0 0 34 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="10" cy="10" r="8.5" stroke="currentColor" stroke-width="2.6" />
                    <circle cx="24" cy="10" r="8.5" stroke="currentColor" stroke-width="2.6" />
                </svg>
                <span class="text-xs uppercase tracking-wider text-[var(--muted)] font-medium">Admin</span>
            </a>

            <a href="{{ url('/') }}" class="text-sm text-[var(--muted)] hover:text-[var(--ink)] transition-colors">
                Back to store
            </a>
        </div>
    </header>

    <main class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-6 py-16">
        <div class="w-full max-w-xl border-1 border-[#cfcfcf] p-10 rounded-[25px]">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>

</html>