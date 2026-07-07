@extends('layout.appLayout')

@section('title', $product->name)

@section('content')

    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-8">

        <!-- Breadcrumb -->
        <a href="{{ route('products.index') }}"
            class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-black transition-colors mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Products
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            <!-- Gallery -->
            <div>
                <div class="bg-gray-50 border border-[#19140010] rounded-2xl aspect-square overflow-hidden mb-4">
                    @if ($product->image_path)
                        <img id="main-image" src="{{ $product->image_url }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-14 h-14 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 7.5L12 3 3 7.5m18 0v9L12 21m9-13.5L12 11 3 7.5M12 11v10" />
                            </svg>
                        </div>
                    @endif
                </div>

                {{--
                    NOTE: your products table only stores a single image_path,
                    so there's only one thumbnail to show right now. The
                    screenshot's 4-thumbnail gallery needs a separate
                    product_images table (product_id, path, sort_order) if
                    you want multiple angles per product — happy to build
                    that migration + upload UI if you want it.
                --}}
                @if ($product->image_url)
                    <div class="grid grid-cols-4 gap-3">
                        <button type="button"
                            class="bg-gray-50 border-2 border-[#1b1b18] rounded-xl aspect-square overflow-hidden">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover">
                        </button>
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div>
                <h1 class="text-2xl font-semibold text-[#1b1b18]">{{ $product->name }}</h1>

                {{--
                    NOTE: no reviews/ratings table exists yet, so there's
                    nothing real to show here. Remove this block entirely,
                    or build a `reviews` table (product_id, user_id, rating,
                    comment) if you want genuine ratings rather than a
                    decorative placeholder.
                --}}

                <p id="display-price" class="text-3xl font-bold text-[#1b1b18] mt-4"
                    data-base-price="{{ $product->price }}">
                    ${{ number_format($product->price, 2) }}
                </p>

                <div class="mt-4 space-y-1.5 text-sm">
                    <p class="text-gray-500">
                        <span class="font-medium text-[#1b1b18]">Product Code:</span>
                        #{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                    {{--
                        NOTE: there's no `stock` column on products yet, so
                        "Availability: In Stock" in the screenshot can't be
                        derived from real data. Add a `stock` integer column
                        via migration if you want this to reflect reality —
                        otherwise this line would just be decorative.
                    --}}
                </div>

                <hr class="border-gray-100 my-6">

                <p class="text-sm text-gray-600 leading-relaxed">{{ $product->description }}</p>

                @if ($product->variants->isNotEmpty())
                    <div class="mt-6">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                            Variant
                        </label>
                        <div id="variant-options" class="flex flex-wrap gap-2">
                            @foreach ($product->variants as $variant)
                                <button type="button" data-variant-id="{{ $variant->id }}"
                                    data-variant-price="{{ $variant->price }}"
                                    data-variant-description="{{ $variant->description }}"
                                    class="variant-option px-4 py-2.5 text-sm font-medium border border-gray-200 rounded-full hover:border-[#1b1b18]/40 transition-colors">
                                    {{ $variant->name }}
                                </button>
                            @endforeach
                        </div>
                        <p id="variant-desc" class="my-[0.8rem] text-[#474747]"></p>
                        <p id="variant-error" class="hidden mt-2 text-xs text-red-500">
                            Please select a variant before adding to cart.
                        </p>
                    </div>
                @endif

                <div class="mt-8 space-y-5">
                    <div>
                        <label for="qty"
                            class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Quantity</label>
                        <input id="qty" type="number" name="quantity" form="add-to-cart-form" value="1"
                            min="1"
                            class="w-24 px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-[#1b1b18]/40 transition-colors">
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <form id="add-to-cart-form" method="POST" action="{{ route('cart.store') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" id="selected-variant-id" value="">
                            <button type="submit" id="add-to-cart-btn"
                                {{ $product->variants->isNotEmpty() ? 'disabled' : '' }}
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-black text-white rounded-full text-sm font-semibold hover:bg-black/90 transition-colors disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-black">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Add to Cart
                            </button>
                        </form>

                        @auth
                            @include('partials.whishlistButton', [
                                'productId' => $product->id,
                                'wrapperClass' => 'w-12 h-12 flex items-center justify-center',
                            ])
                        @else
                            <a href="{{ route('login') }}"
                                class="w-12 h-12 flex items-center justify-center border border-gray-200 rounded-full text-gray-600 hover:text-rose-500 hover:border-rose-200 transition-colors"
                                aria-label="Log in to add to wishlist">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 21s-7.5-4.9-10.2-9.3C.2 8.9 1.4 5.5 4.6 4.5c2-.6 4 .2 5.4 2 .4.5 1.2 1.5 2 2.5.8-1 1.6-2 2-2.5 1.4-1.8 3.4-2.6 5.4-2 3.2 1 4.4 4.4 2.8 7.2C19.5 16.1 12 21 12 21z" />
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Related products -->
    @if ($relatedProducts->isNotEmpty())
        <div class="mt-20 max-w-7xl mx-auto px-6 lg:px-10">
            <h2 class="text-lg font-semibold text-[#1b1b18] mb-6">You might also like</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                @foreach ($relatedProducts as $related)
                    <a href="{{ route('products.show', $related) }}" class="group block">
                        <div class="bg-gray-50 border border-[#19140010] rounded-2xl aspect-square overflow-hidden mb-3">
                            @if ($related->image_url)
                                <img src="{{ $related->image_url }}" alt="{{ $related->name }}"
                                    class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300">
                            @endif
                        </div>
                        <h3 class="text-sm font-semibold text-[#1b1b18] truncate">{{ $related->name }}</h3>
                        <p class="text-sm font-bold text-[#1b1b18] mt-1">${{ number_format($related->price, 2) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const variantButtons = document.querySelectorAll('.variant-option');
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            const selectedVariantInput = document.getElementById('selected-variant-id');
            const displayPrice = document.getElementById('display-price');
            const variantError = document.getElementById('variant-error');
            const variantDesc = document.getElementById("variant-desc");


            if (variantButtons.length === 0 || !displayPrice) return;

            const basePrice = parseFloat(displayPrice.dataset.basePrice);

            function formatPrice(value) {
                return '$' + value.toFixed(2);
            }

            variantButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const isAlreadySelected = btn.classList.contains('border-black');

                    variantButtons.forEach(b => {
                        b.classList.remove('border-black', 'bg-gray-50', 'ring-1',
                            'ring-black');
                        b.classList.add('border-gray-200');
                    });

                    if (isAlreadySelected) {
                        // Deselect
                        selectedVariantInput.value = '';
                        displayPrice.textContent = formatPrice(basePrice);
                        addToCartBtn.disabled = true;
                        return;
                    }

                    btn.classList.remove('border-gray-200');
                    btn.classList.add('border-black', 'bg-gray-50', 'ring-1', 'ring-black');

                    const variantPrice = parseFloat(btn.dataset.variantPrice);
                    const variantDescription = btn.dataset.variantDescription;
                    variantDesc.textContent = variantDescription;
                    selectedVariantInput.value = btn.dataset.variantId;

                    displayPrice.textContent = formatPrice(basePrice + variantPrice);
                    addToCartBtn.disabled = false;
                    variantError.classList.add('hidden');
                });
            });

            document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
                if (variantButtons.length > 0 && !selectedVariantInput.value) {
                    e.preventDefault();
                    variantError.classList.remove('hidden');
                }
            });
        });
    </script>
@endpush
