@extends('appAdminLayout.layout')

@section('title', 'Add Variant')
@section('page-title', 'Add Variant')

@section('content')

    <a href="{{ route('admin.variants.index') }}"
        class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-black transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Variants
    </a>

    <div class="bg-white border border-[#19140010] rounded-2xl p-8 max-w-2xl">
        <form method="POST" action="{{ route('admin.variants.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-[#1b1b18] mb-2">Variant Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                    placeholder="e.g. Red Frame, Polarized Lenses"
                    class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-[#1b1b18] mb-2">Additional Price (MAD)</label>
                <div class="relative">
                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-sm text-gray-400">DH</span>
                    <input id="price" type="number" step="0.01" min="0" name="price"
                        value="{{ old('price') }}" required placeholder="0.00"
                        class="w-full pl-8 pr-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors">
                </div>
                @error('price')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-[#1b1b18] mb-2">Description</label>
                <textarea id="description" name="description" rows="4"
                    placeholder="Optional description of this variant..."
                    class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors resize-none">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-2.5 bg-black text-white rounded-full text-sm font-semibold hover:bg-black/90 transition-colors">
                    Create Variant
                </button>
                <a href="{{ route('admin.variants.index') }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 border border-gray-200 rounded-full text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
