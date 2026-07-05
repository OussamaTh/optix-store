@extends('appAdminLayout.layout')

@section('title', 'Edit Variant')
@section('page-title', 'Edit Variant')

@section('content')

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-6">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('admin.variants.index') }}"
        class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-black transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Variants
    </a>

    <div class="bg-white border border-[#19140010] rounded-2xl p-8 max-w-xl">
        <form method="POST" action="{{ route('admin.variants.update', $variant) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-[#1b1b18] mb-2">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $variant->name) }}" required
                    class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-[#1b1b18] mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors resize-none">{{ old('description', $variant->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-[#1b1b18] mb-2">Additional Price (USD)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">+$</span>
                    <input id="price" type="number" step="0.01" min="0" name="price"
                        value="{{ old('price', $variant->price) }}" required
                        class="w-full pl-9 pr-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors">
                </div>
                @error('price')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-2.5 bg-black text-white rounded-full text-sm font-semibold hover:bg-black/90 transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.variants.index') }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 border border-gray-200 rounded-full text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection