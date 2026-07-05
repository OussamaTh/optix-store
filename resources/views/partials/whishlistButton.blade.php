@php
    $wishlistIds = auth()->check() ? auth()->user()->wishlistProducts()->pluck('products.id')->toArray() : [];
    $wrapperClass = $wrapperClass ?? 'absolute top-3 right-3 z-10';
@endphp

<form method="POST" action="{{ route('account.wishlist.toggle') }}" class="wishlist-form {{ $wrapperClass }}"
    data-product-id="{{ $productId }}">
    @csrf
    <input type="hidden" name="product_id" value="{{ $productId }}">
    <button type="submit"
        class="wishlist-btn p-2 bg-white/85 hover:bg-white text-gray-400 hover:text-red-500 rounded-full border border-gray-100 shadow-sm transition-all focus:outline-none cursor-pointer"
        aria-label="Toggle Wishlist">
        <svg class="wishlist-icon w-4 h-4 {{ in_array($productId, $wishlistIds) ? 'fill-red-500 text-red-500' : 'text-gray-400' }}"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>
</form>
@push('scripts')
    <script>
        document.addEventListener('submit', async (e) => {
            const form = e.target.closest('.wishlist-form');
            if (!form) return;

            e.preventDefault();

            const button = form.querySelector('.wishlist-btn');
            const icon = form.querySelector('.wishlist-icon');

            // Prevent double-submits while a request is in flight.
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
                    }),
                });

                if (response.status === 401) {
                    // Not logged in — send them to login instead of failing silently.
                    window.location.href = '{{ route('login') }}';
                    return;
                }

                if (!response.ok) {
                    throw new Error('Request failed');
                }

                const data = await response.json();

                // Expecting { wishlisted: true } or { wishlisted: false }
                icon.classList.toggle('fill-red-500', data.wishlisted);
                icon.classList.toggle('text-red-500', data.wishlisted);
                icon.classList.toggle('text-gray-400', !data.wishlisted);
            } catch (error) {
                console.error('Wishlist toggle failed:', error);
            } finally {
                button.disabled = false;
            }
        });
    </script>
@endpush
