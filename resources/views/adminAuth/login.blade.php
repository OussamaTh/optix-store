@extends('adminAuth.layout')

@section('title', 'Admin Sign in')

@section('content')

    <h1 class="text-3xl font-semibold tracking-tight text-[var(--ink)]">
        Admin Portal
    </h1>
    <p class="mt-2 text-sm text-[var(--muted)]">
        Sign in to manage products, orders, and your store.
    </p>

    @if ($errors->any())
        <div class="mt-6 text-sm text-red-600 space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login') }}" class="mt-8 space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-[var(--ink)]">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username" placeholder="admin@example.com"
                class="mt-2 block w-full border-0 border-b border-[var(--hairline)] bg-transparent px-2 py-2.5 text-[var(--ink)] placeholder:text-gray-400 focus:border-[var(--ink)] focus:ring-0 transition-colors">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-[var(--ink)]">
                Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••"
                class="mt-2 block w-full border-0 border-b border-[var(--hairline)] bg-transparent px-2 py-2.5 text-[var(--ink)] placeholder:text-gray-400 focus:border-[var(--ink)] focus:ring-0 transition-colors">
        </div>

        <label for="remember" class="flex items-center gap-2 select-none cursor-pointer">
            <input id="remember" type="checkbox" name="remember"
                class="rounded border-gray-300 text-[var(--ink)] focus:ring-[var(--ink)]">
            <span class="text-sm text-[var(--muted)]">Remember me</span>
        </label>

        <button type="submit"
            class="w-full inline-flex items-center justify-center rounded-full bg-[var(--ink)] px-6 py-3 text-sm font-semibold text-white hover:bg-black/85 transition-colors">
            Sign in
        </button>
    </form>

    <p class="mt-8 text-sm text-[var(--muted)] text-center">
        Setting up your store for the first time?
        <a href="{{ route('admin.register') }}" class="font-medium text-[var(--ink)] hover:underline">
            Create an admin account
        </a>
    </p>

@endsection
