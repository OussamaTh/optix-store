@extends('appAdminLayout.layout')

@section('title', 'Variants')
@section('page-title', 'Variants')

@section('content')

    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-black text-white text-xs rounded-full inline-block">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-full inline-block">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <form method="GET" action="{{ route('admin.variants.index') }}" class="flex-1 max-w-sm">
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search variants..."
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-white border border-[#19140010] rounded-full focus:outline-none focus:border-[#1b1b18]/30 transition-colors">
            </div>
        </form>

        <a href="{{ route('admin.variants.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full text-xs font-semibold hover:bg-black/90 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Variant
        </a>
    </div>

    @if ($variants->isEmpty())
        <div class="border border-dashed border-gray-200 rounded-3xl p-16 text-center">
            <div
                class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-[#1b1b18] mb-1">No variants yet</h3>
            <p class="text-gray-400 text-xs max-w-xs mx-auto mb-6">Add your first variant to associate with products.</p>
            <a href="{{ route('admin.variants.create') }}"
                class="inline-flex items-center justify-center px-6 py-2.5 bg-black text-white rounded-full text-xs font-medium hover:bg-black/90 transition-colors">
                Add Variant
            </a>
        </div>
    @else
        <div class="bg-white border border-[#19140010] rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left font-medium text-gray-400 text-xs uppercase tracking-wider px-6 py-4">
                            Name
                        </th>
                        <th class="text-left font-medium text-gray-400 text-xs uppercase tracking-wider px-6 py-4">
                            Additional Price
                        </th>
                        <th class="text-left font-medium text-gray-400 text-xs uppercase tracking-wider px-6 py-4">
                            Description
                        </th>
                        <th class="text-right font-medium text-gray-400 text-xs uppercase tracking-wider px-6 py-4">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($variants as $variant)
                        <tr class="hover:bg-gray-50/40 transition-colors">
                            <td class="px-6 py-4 font-semibold text-[#1b1b18]">
                                {{ $variant->name }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-[#1b1b18]">
                                +${{ number_format($variant->price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                                {{ $variant->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.variants.edit', $variant) }}"
                                        class="text-xs font-semibold text-gray-500 hover:text-black transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.variants.destroy', $variant) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this variant?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $variants->links() }}
        </div>
    @endif

@endsection
