@extends('auth.authLayout')

@section('title', 'Sign in')

@section('content')

    <h1 class="text-3xl font-semibold tracking-tight text-[var(--ink)]">
        Welcome back
    </h1>
    <p class="mt-2 text-sm text-[var(--muted)]">
        Sign in to manage your orders and saved frames.
    </p>

    {{-- Session status (e.g. password reset link sent) --}}
    @if (session('status'))
        <div class="mt-6 text-sm text-[var(--ink)] bg-gray-50 border border-[var(--hairline)] rounded-lg px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="mt-6 text-sm text-red-600 space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-[var(--ink)]">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username" placeholder="you@example.com"
                class="mt-2 block w-full border-0 border-b border-[var(--hairline)] bg-transparent px-2 py-2.5 text-[var(--ink)] placeholder:text-gray-400 focus:border-[var(--ink)] focus:ring-0 transition-colors">
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-[var(--ink)]">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-[var(--muted)] hover:text-[var(--ink)] transition-colors">
                        Forgot it?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••"
                class="mt-2 block w-full border-0 border-b border-[var(--hairline)] bg-transparent px-2 py-2.5 text-[var(--ink)] placeholder:text-gray-400 focus:border-[var(--ink)] focus:ring-0 transition-colors">
        </div>

        {{-- Remember me --}}
        <label for="remember" class="flex items-center gap-2 select-none cursor-pointer">
            <input id="remember" type="checkbox" name="remember"
                class="rounded border-gray-300 text-[var(--ink)] focus:ring-[var(--ink)]">
            <span class="text-sm text-[var(--muted)]">Remember me</span>
        </label>

        {{-- Submit --}}
        <button type="submit"
            class="w-full inline-flex items-center justify-center rounded-full bg-[var(--ink)] px-6 py-3 text-sm font-semibold text-white hover:bg-black/85 transition-colors">
            Sign in
        </button>
    </form>

    {{-- Divider --}}
    <div class="mt-8 flex items-center gap-3">
        <span class="h-px flex-1 bg-[var(--hairline)]"></span>
        <span class="text-xs uppercase tracking-wide text-gray-400">or</span>
        <span class="h-px flex-1 bg-[var(--hairline)]"></span>
    </div>

    {{-- Google sign-in --}}
    <a href="{{ route('auth.google') }}"
        class="mt-6 w-full inline-flex items-center justify-center gap-3 rounded-full border border-[var(--hairline)] px-6 py-3 text-sm font-medium text-[var(--ink)] hover:border-[var(--ink)] transition-colors">
        <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
            <path fill="#4285F4"
                d="M17.64 9.2c0-.64-.06-1.25-.16-1.84H9v3.48h4.84c-.21 1.13-.85 2.09-1.81 2.73v2.27h2.92c1.71-1.57 2.69-3.89 2.69-6.64z" />
            <path fill="#34A853"
                d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.92-2.27c-.81.54-1.84.86-3.04.86-2.34 0-4.32-1.58-5.03-3.71H.96v2.33C2.44 15.98 5.48 18 9 18z" />
            <path fill="#FBBC05"
                d="M3.97 10.7c-.18-.54-.28-1.11-.28-1.7s.1-1.16.28-1.7V4.97H.96A8.997 8.997 0 0 0 0 9c0 1.45.35 2.83.96 4.03l3.01-2.33z" />
            <path fill="#EA4335"
                d="M9 3.58c1.32 0 2.5.45 3.44 1.35l2.58-2.58C13.47.89 11.43 0 9 0 5.48 0 2.44 2.02.96 4.97l3.01 2.33C4.68 5.16 6.66 3.58 9 3.58z" />
        </svg>
        Continue with Google
    </a>

    <p class="mt-8 text-sm text-[var(--muted)] text-center">
        New here?
        <a href="{{ route('register') }}" class="font-medium text-[var(--ink)] hover:underline">
            Create an account
        </a>
    </p>

@endsection
