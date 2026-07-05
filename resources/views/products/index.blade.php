@extends('layout.appLayout')

@section('title', 'Products')

@section('content')

    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-10">

        <!-- Top bar: title + search + sort -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-semibold text-[#1b1b18]">All Products</h1>
                <p class="text-sm text-gray-400 mt-1">
                    {{ $products->total() }} {{ Str::plural('item', $products->total()) }}
                    @if (request()->filled('q'))
                        for "<span class="font-medium text-[#1b1b18]">{{ request('q') }}</span>"
                    @endif
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                    <input type="text" form="filters-form" name="q" value="{{ request('q') }}"
                        placeholder="Search products..."
                        class="pl-10 pr-4 py-2.5 text-sm bg-white border border-gray-200 rounded-full w-64 focus:outline-none focus:border-[#1b1b18]/40 transition-colors">
                </div>

                <select form="filters-form" name="sort" onchange="this.form.submit()"
                    class="text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-full px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
                    <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                </select>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            <aside class="lg:w-60 shrink-0">
                <form id="filters-form" method="GET" action="{{ route('products.index') }}"
                    class="lg:sticky lg:top-24 space-y-1">

                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-[#1b1b18]">Filters</h2>
                        @if (request()->hasAny(['gender', 'category', 'price_min', 'price_max', 'q']))
                            <a href="{{ route('products.index') }}"
                                class="text-xs font-semibold text-gray-400 hover:text-rose-500 transition-colors">
                                Clear all
                            </a>
                        @endif
                    </div>

                    <!-- Gender -->
                    <details class="group border-b border-gray-100 py-4" open>
                        <summary class="flex items-center justify-between cursor-pointer list-none select-none">
                            <span class="text-sm font-semibold text-[#1b1b18]">Gender</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="mt-3 space-y-2.5">
                            @foreach (['men' => 'Men', 'women' => 'Women', 'unisex' => 'Unisex'] as $value => $label)
                                <label class="flex items-center justify-between gap-2 cursor-pointer group/option">
                                    <span class="flex items-center gap-2.5">
                                        <input type="checkbox" name="gender[]" value="{{ $value }}"
                                            @checked(in_array($value, (array) request('gender', []))) onchange="this.form.submit()"
                                            class="w-4 h-4 rounded border-gray-300 text-[#1b1b18] focus:ring-[#1b1b18]/30">
                                        <span
                                            class="text-sm text-gray-600 group-hover/option:text-[#1b1b18] transition-colors">{{ $label }}</span>
                                    </span>
                                    <span class="text-xs text-gray-400">({{ $genderCounts[$value] ?? 0 }})</span>
                                </label>
                            @endforeach
                        </div>
                    </details>

                    <!-- Category -->
                    <details class="group border-b border-gray-100 py-4" open>
                        <summary class="flex items-center justify-between cursor-pointer list-none select-none">
                            <span class="text-sm font-semibold text-[#1b1b18]">Category</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="mt-3 space-y-2.5">
                            @foreach (['glasses' => 'Glasses', 'sunglasses' => 'Sunglasses'] as $value => $label)
                                <label class="flex items-center justify-between gap-2 cursor-pointer group/option">
                                    <span class="flex items-center gap-2.5">
                                        <input type="checkbox" name="category[]" value="{{ $value }}"
                                            @checked(in_array($value, (array) request('category', []))) onchange="this.form.submit()"
                                            class="w-4 h-4 rounded border-gray-300 text-[#1b1b18] focus:ring-[#1b1b18]/30">
                                        <span
                                            class="text-sm text-gray-600 group-hover/option:text-[#1b1b18] transition-colors">{{ $label }}</span>
                                    </span>
                                    <span class="text-xs text-gray-400">({{ $categoryCounts[$value] ?? 0 }})</span>
                                </label>
                            @endforeach
                        </div>
                    </details>

                    <!-- Price -->
                    <details class="group py-4" open>
                        <summary class="flex items-center justify-between cursor-pointer list-none select-none">
                            <span class="text-sm font-semibold text-[#1b1b18]">Price</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="mt-4">
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                                    <input type="number" name="price_min" value="{{ request('price_min') }}"
                                        placeholder="{{ $priceRange->min_price ? number_format($priceRange->min_price, 0) : '0' }}"
                                        class="w-full pl-6 pr-2 py-2 text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-[#1b1b18]/40 transition-colors">
                                </div>
                                <span class="text-gray-300 text-xs">–</span>
                                <div class="relative flex-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                                    <input type="number" name="price_max" value="{{ request('price_max') }}"
                                        placeholder="{{ $priceRange->max_price ? number_format($priceRange->max_price, 0) : '0' }}"
                                        class="w-full pl-6 pr-2 py-2 text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-[#1b1b18]/40 transition-colors">
                                </div>
                            </div>
                            <button type="submit"
                                class="mt-3 w-full text-xs font-semibold text-white bg-black hover:bg-black/90 rounded-lg py-2 transition-colors">
                                Apply
                            </button>
                        </div>
                    </details>

                </form>
            </aside>

            <!-- Product grid -->
            <div class="flex-1 min-w-0">
                @if ($products->isEmpty())
                    <div class="border border-dashed border-gray-200 rounded-3xl p-20 text-center">
                        <div
                            class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-[#1b1b18] mb-1">No products found</h3>
                        <p class="text-gray-400 text-sm max-w-xs mx-auto">
                            @if (request()->filled('q'))
                                We couldn't find anything matching "{{ request('q') }}". Try a different search.
                            @else
                                No products match these filters. Try clearing some.
                            @endif
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-8">
                        @foreach ($products as $product)
                            <a href="{{ route('products.show', $product) }}"
                                class="group block flex flex-col justify-between">
                                <div
                                    class="relative bg-gray-50 border border-[#19140010] rounded-2xl aspect-square overflow-hidden mb-4">
                                    @if ($product->image_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($product->image_path) }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor"
                                                stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M21 7.5L12 3 3 7.5m18 0v9L12 21m9-13.5L12 11 3 7.5M12 11v10" />
                                            </svg>
                                        </div>
                                    @endif

                                    @include('partials.whishlistButton', [
                                        'productId' => $product->id,
                                    ])

                                </div>

                                <h3 class="text-sm font-semibold text-[#1b1b18] truncate">{{ $product->name }}</h3>
                                <p class="text-xs text-gray-400 mt-0.5 line-clamp-2">{{ $product->description }}</p>
                                </p>
                                <div class="flex justify-between">
                                    <p class="text-sm font-bold text-[#1b1b18] mt-2">
                                        ${{ number_format($product->price, 2) }}
                                    </p>
                                    <div class="">
                                        <form class="add-to-cart-form flex-1" method="POST"
                                            action="{{ route('cart.store') }}">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1" min="1">
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit"
                                                class="add-to-cart-btn inline-flex items-center justify-center gap-2 px-4 py-2 bg-black text-white rounded-full text-sm font-semibold hover:bg-black/90 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                                <svg class="w-4 h-4 cart-icon" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <span class="cart-btn-text">Add to Cart</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
    @push('scripts')
        <script>
            document.addEventListener('submit', async (e) => {
                const form = e.target.closest('.add-to-cart-form');
                if (!form) return;

                e.preventDefault();

                const button = form.querySelector('.add-to-cart-btn');
                const icon = form.querySelector('.cart-icon');
                const text = form.querySelector('.cart-btn-text');

                if (button.disabled) return;
                button.disabled = true;

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            product_id: form.querySelector('input[name="product_id"]').value,
                            quantity: form.querySelector('input[name="quantity"]').value,
                        }),
                    });

                    if (response.status === 401) {
                        window.location.href = '{{ route('login') }}';
                        return;
                    }

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Request failed');
                    }

                    const data = await response.json();

                    // === INJECT NEW CART ITEM ===
                    const cartItemsWrapper = document.getElementById('cart-items-wrapper');
                    const emptyState = document.getElementById('cart-empty-state');
                    const cartFooter = document.getElementById('cart-footer');

                    if (cartItemsWrapper && data.cartItemHtml) {
                        // Remove empty state if it exists
                        if (emptyState) {
                            emptyState.remove();
                        }

                        // Append new item
                        cartItemsWrapper.insertAdjacentHTML('beforeend', data.cartItemHtml);
                    }

                    // Show footer if it was hidden
                    if (cartFooter) {
                        cartFooter.classList.remove('hidden');
                    }

                    // Update totals
                    updateCartTotals(data);

                    // Change button to success state
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />';
                    text.textContent = 'Added to Cart';
                    button.classList.remove('bg-black');
                    button.classList.add('bg-emerald-600');

                    // Update cart count badge
                    updateCartBadge(data.cartCount);

                    // Open cart sidebar
                    if (typeof openCartSidebar === 'function') {
                        openCartSidebar();
                    }

                    // Revert button after 3 seconds
                    setTimeout(() => {
                        icon.innerHTML =
                            '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />';
                        text.textContent = 'Add to Cart';
                        button.classList.remove('bg-emerald-600');
                        button.classList.add('bg-black');
                        button.disabled = false;
                    }, 3000);

                } catch (error) {
                    console.error('Add to cart failed:', error);
                    button.disabled = false;
                }
            });

            // Helper to update cart totals
            function updateCartTotals(data) {
                const cartItem = data.cartItem;
                if (!cartItem) return;

                const subtotalEl = document.getElementById('cart-subtotal');
                const totalEl = document.getElementById('cart-total');

                if (subtotalEl && totalEl && cartItem.product) {
                    // Calculate new subtotal from all items (simplified — you might want to return new totals from server)
                    const currentSubtotal = parseFloat(subtotalEl.textContent.replace('$', '').replace(',', ''));
                    const itemTotal = cartItem.product.price * cartItem.quantity;
                    const newSubtotal = currentSubtotal + itemTotal;

                    subtotalEl.textContent = '$' + newSubtotal.toFixed(2);
                    totalEl.textContent = '$' + newSubtotal.toFixed(2);
                }
            }
        </script>
    @endpush

@endsection
