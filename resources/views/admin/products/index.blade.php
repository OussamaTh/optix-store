@extends('appAdminLayout.layout')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')

    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-black text-white text-xs rounded-full inline-block">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex-1 max-w-sm">
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..."
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-white border border-[#19140010] rounded-full focus:outline-none focus:border-[#1b1b18]/30 transition-colors">
            </div>
        </form>

        <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full text-xs font-semibold hover:bg-black/90 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Product
        </a>
    </div>

    @if ($products->isEmpty())
        <div class="border border-dashed border-gray-200 rounded-3xl p-16 text-center">
            <div
                class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.59 13.41L11 23l-9-9 9.59-9.59a2 2 0 011.41-.41H20a2 2 0 012 2v6.59a2 2 0 01-.41 1.41z" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-[#1b1b18] mb-1">No products yet</h3>
            <p class="text-gray-400 text-xs max-w-xs mx-auto mb-6">Add your first product to start selling.</p>
            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center justify-center px-6 py-2.5 bg-black text-white rounded-full text-xs font-medium hover:bg-black/90 transition-colors">
                Add Product
            </a>
        </div>
    @else
        <form id="bulk-delete-form" method="POST" action="{{ route('admin.products.bulkDestroy') }}">
            @csrf
            @method('DELETE')

            <div class="bg-white border border-[#19140010] rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="px-6 py-4 w-10">
                                <input type="checkbox" id="select-all"
                                    class="w-4 h-4 rounded border-gray-300 text-black focus:ring-black/20 cursor-pointer">
                            </th>
                            <th class="text-left font-medium text-gray-400 text-xs uppercase tracking-wider px-6 py-4">
                                Product</th>
                            <th class="text-left font-medium text-gray-400 text-xs uppercase tracking-wider px-6 py-4">Price
                            </th>
                            <th class="text-left font-medium text-gray-400 text-xs uppercase tracking-wider px-6 py-4">Added
                            </th>
                            <th class="text-right font-medium text-gray-400 text-xs uppercase tracking-wider px-6 py-4">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($products as $product)
                            <tr class="hover:bg-gray-50/40 transition-colors" data-row-id="{{ $product->id }}">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="ids[]" value="{{ $product->id }}"
                                        class="row-checkbox w-4 h-4 rounded border-gray-300 text-black focus:ring-black/20 cursor-pointer">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-gray-50 rounded-xl overflow-hidden flex items-center justify-center shrink-0 border border-gray-100">
                                            @if ($product->image_url)
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                    class="w-full h-full object-contain p-1">
                                            @else
                                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#1b1b18]">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-400 truncate max-w-xs">
                                                {{ Str::limit($product->description, 60) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-[#1b1b18]">{{ number_format($product->price, 2) }} DH
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">{{ $product->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 rounded-full text-xs font-semibold hover:bg-gray-50 text-black transition-colors">
                                            Edit
                                        </a>
                                        <button type="button" data-single-delete="{{ $product->id }}"
                                            data-delete-url="{{ route('admin.products.destroy', $product) }}"
                                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 rounded-full text-xs font-semibold hover:bg-red-50 hover:border-red-200 hover:text-red-600 text-gray-500 transition-colors">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-6 admin-pagination">
            {{ $products->links() }}
        </div>

        {{-- Floating bulk action island --}}
        <div id="bulk-island"
            class="fixed bottom-6 right-6 z-50 hidden items-center gap-4 bg-black text-white pl-5 pr-3 py-3 rounded-full shadow-lg shadow-black/20">
            <span id="bulk-count" class="text-xs font-medium whitespace-nowrap">0 selected</span>
            <button type="button" id="bulk-delete-btn"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-black rounded-full text-xs font-semibold hover:bg-gray-100 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete
            </button>
            <button type="button" id="bulk-clear"
                class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Single-delete confirm reuses the same bulk form/route pattern but posts to the per-product route --}}
        <form id="single-delete-form" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const island = document.getElementById('bulk-island');
            const bulkCount = document.getElementById('bulk-count');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            const bulkClear = document.getElementById('bulk-clear');
            const bulkForm = document.getElementById('bulk-delete-form');

            function refreshIsland() {
                const checked = document.querySelectorAll('.row-checkbox:checked');
                if (checked.length > 0) {
                    island.classList.remove('hidden');
                    island.classList.add('flex');
                    bulkCount.textContent = checked.length + ' selected';
                } else {
                    island.classList.add('hidden');
                    island.classList.remove('flex');
                }
                if (selectAll) {
                    selectAll.checked = checked.length === checkboxes.length && checkboxes.length > 0;
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                    refreshIsland();
                });
            }

            checkboxes.forEach(cb => cb.addEventListener('change', refreshIsland));

            bulkClear.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = false);
                refreshIsland();
            });

            bulkDeleteBtn.addEventListener('click', function() {
                const checked = document.querySelectorAll('.row-checkbox:checked');
                if (checked.length === 0) return;
                if (!confirm('Delete ' + checked.length + ' selected product(s)? This cannot be undone.'))
                    return;
                bulkForm.submit();
            });

            // Single-row delete now goes through JS so it shares the same confirm UX
            document.querySelectorAll('[data-single-delete]').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!confirm('Delete this product? This cannot be undone.')) return;
                    const form = document.getElementById('single-delete-form');
                    form.action = btn.dataset.deleteUrl;
                    form.submit();
                });
            });
        });
    </script>
@endpush
