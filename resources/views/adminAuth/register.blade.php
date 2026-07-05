@extends('adminAuth.layout')

@section('title', 'Create Admin Account')

@section('content')

    <h1 class="text-3xl font-semibold tracking-tight text-[var(--ink)]">
        Create an admin account
    </h1>
    <p class="mt-2 text-sm text-[var(--muted)]">
        This account will have full access to manage products and orders.
    </p>

    @if ($errors->any())
        <div class="mt-6 text-sm text-red-600 space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.register') }}" class="mt-8 space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-[var(--ink)]">
                Full name
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                autocomplete="name" placeholder="Jane Doe"
                class="mt-2 block w-full border-0 border-b border-[var(--hairline)] bg-transparent px-2 py-2.5 text-[var(--ink)] placeholder:text-gray-400 focus:border-[var(--ink)] focus:ring-0 transition-colors">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-[var(--ink)]">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                autocomplete="username" placeholder="admin@example.com"
                class="mt-2 block w-full border-0 border-b border-[var(--hairline)] bg-transparent px-2 py-2.5 text-[var(--ink)] placeholder:text-gray-400 focus:border-[var(--ink)] focus:ring-0 transition-colors">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-[var(--ink)]">
                Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                placeholder="••••••••"
                class="mt-2 block w-full border-0 border-b border-[var(--hairline)] bg-transparent px-2 py-2.5 text-[var(--ink)] placeholder:text-gray-400 focus:border-[var(--ink)] focus:ring-0 transition-colors">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-[var(--ink)]">
                Confirm password
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" placeholder="••••••••"
                class="mt-2 block w-full border-0 border-b border-[var(--hairline)] bg-transparent px-2 py-2.5 text-[var(--ink)] placeholder:text-gray-400 focus:border-[var(--ink)] focus:ring-0 transition-colors">
        </div>

        <button type="submit"
            class="w-full inline-flex items-center justify-center rounded-full bg-[var(--ink)] px-6 py-3 text-sm font-semibold text-white hover:bg-black/85 transition-colors">
            Create account
        </button>
    </form>

    <p class="mt-8 text-sm text-[var(--muted)] text-center">
        Already have an account?
        <a href="{{ route('admin.login') }}" class="font-medium text-[var(--ink)] hover:underline">
            Sign in
        </a>
    </p>

@endsection