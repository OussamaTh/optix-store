@php
    $cartItems = $cartItems ?? (auth()->check() ? auth()->user()->cartItems()->with('product')->get() : collect());
    $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
    $delivery = 0;
    $total = $subtotal + $delivery;
@endphp

<!-- Backdrop -->
<div id="cart-backdrop"
    class="fixed inset-0 bg-black/30 backdrop-blur-[2px] z-[60] hidden opacity-0 transition-opacity duration-300">
</div>

<!-- Sidebar panel -->
<aside id="cart-sidebar"
    class="fixed top-0 right-0 h-full w-full sm:w-[400px] bg-white z-[70] translate-x-full transition-transform duration-300 ease-out shadow-2xl flex flex-col"
    role="dialog" aria-modal="true" aria-label="Shopping cart">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 h-20 border-b border-[#19140010] shrink-0">
        <h2 class="text-lg font-semibold text-[#1b1b18]">Cart</h2>
        <button id="cart-close-btn" aria-label="Close cart"
            class="p-1.5 text-gray-400 hover:text-[#1b1b18] transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Items -->
    <div id="cart-items-wrapper" class="flex-1 overflow-y-auto px-6 py-5 space-y-6">
        @forelse ($cartItems as $item)
            @include('partials.cart-item', ['item' => $item])
        @empty
            <div id="cart-empty-state" class="h-full flex flex-col items-center justify-center text-center py-20">
                <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <p class="text-sm font-semibold text-[#1b1b18]">Your cart is empty</p>
                <p class="text-xs text-gray-400 mt-1">Items you add will show up here.</p>
            </div>
        @endforelse
    </div>

    <!-- Footer / totals -->
    <div id="cart-footer"
        class="{{ $cartItems->isEmpty() ? 'hidden' : '' }} border-t border-[#19140010] px-6 py-5 space-y-3 shrink-0">
        <div class="space-y-1.5 text-xs">
            <div class="flex justify-between text-gray-500">
                <span>Subtotal:</span>
                <span id="cart-subtotal" class="font-medium text-[#1b1b18]">${{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between text-gray-500">
                <span>Delivery:</span>
                <span id="cart-delivery"
                    class="font-medium text-[#1b1b18]">{{ $delivery > 0 ? '$' . number_format($delivery, 2) : 'Free' }}</span>
            </div>
            <div class="flex justify-between text-sm font-bold text-[#1b1b18] pt-1">
                <span>Total:</span>
                <span id="cart-total">${{ number_format($total, 2) }}</span>
            </div>
        </div>

        <a href="{{ route('checkout.index') }}"
            class="block w-full text-center bg-black text-white text-sm font-semibold py-3 rounded-full hover:bg-black/90 transition-colors">
            Checkout
        </a>

        <a href="{{ route('cart.index') }}"
            class="block text-center text-[11px] text-gray-400 hover:text-[#1b1b18] underline underline-offset-2 transition-colors">
            Shipping Details
        </a>
    </div>
</aside>

{{-- <script>
    // Cart sidebar open/close functions (global scope)
    function openCartSidebar() {
        const backdrop = document.getElementById('cart-backdrop');
        const sidebar = document.getElementById('cart-sidebar');

        if (backdrop && sidebar) {
            backdrop.classList.remove('hidden');
            // Force reflow
            void backdrop.offsetWidth;
            backdrop.classList.remove('opacity-0');

            sidebar.classList.remove('translate-x-full');
        }
    }

    function closeCartSidebar() {
        const backdrop = document.getElementById('cart-backdrop');
        const sidebar = document.getElementById('cart-sidebar');

        if (backdrop && sidebar) {
            backdrop.classList.add('opacity-0');
            sidebar.classList.add('translate-x-full');

            setTimeout(() => {
                backdrop.classList.add('hidden');
            }, 300);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const closeBtn = document.getElementById('cart-close-btn');
        const backdrop = document.getElementById('cart-backdrop');

        if (closeBtn) {
            closeBtn.addEventListener('click', closeCartSidebar);
        }
        if (backdrop) {
            backdrop.addEventListener('click', closeCartSidebar);
        }
    });
</script> --}}
