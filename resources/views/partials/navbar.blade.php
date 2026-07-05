@include('partials.cartSidebar')

<header class="w-full bg-[#FDFDFC]/80 backdrop-blur-md sticky top-0 z-40 border-b border-[#19140010]">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('main-page') }}" class="flex items-center space-x-2" aria-label="Brand Home">
            <svg class="w-8 h-8 text-[#1b1b18]" viewBox="0 0 100 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Left ring -->
                <circle cx="30" cy="25" r="20" stroke="currentColor" stroke-width="8" fill="none" />
                <!-- Right ring -->
                <circle cx="70" cy="25" r="20" stroke="currentColor" stroke-width="8" fill="none" />
                <!-- Bridge connection -->
                <path d="M48 25H52" stroke="currentColor" stroke-width="8" stroke-linecap="round" />
            </svg>
        </a>

        <!-- Nav Links -->
        <nav class="hidden md:flex items-center space-x-8 text-sm font-medium">
            <a href="{{ route('main-page') }}"
                class="text-[#1b1b18] hover:opacity-75 transition-opacity py-2 border-b-2 transition-colors {{ request()->routeIs('main-page') ? 'border-[#1b1b18]' : 'border-transparent hover:border-[#1b1b18]/30' }}">Home</a>
            <a href="{{ route('products.index') }}"
                class="text-[#1b1b18] hover:opacity-75 transition-opacity py-2 border-b-2 transition-colors {{ request()->routeIs('products.*') ? 'border-[#1b1b18]' : 'border-transparent hover:border-[#1b1b18]/30' }}">Products</a>
            <a href="#customize"
                class="text-[#1b1b18] hover:opacity-75 transition-opacity py-2 border-b-2 border-transparent hover:border-[#1b1b18]/30">Customize</a>
            <a href="#lenses"
                class="text-[#1b1b18] hover:opacity-75 transition-opacity py-2 border-b-2 border-transparent hover:border-[#1b1b18]/30">Lenses</a>
            <a href="#contact"
                class="text-[#1b1b18] hover:opacity-75 transition-opacity py-2 border-b-2 border-transparent hover:border-[#1b1b18]/30">Contact</a>
        </nav>

        <!-- Right Action Icons -->
        <div class="flex items-center space-x-5">
            <button class="p-1 hover:opacity-70 transition-opacity" aria-label="Search">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            @auth
                <!-- User Profile Dropdown -->
                <div class="relative" id="user-dropdown">
                    <button id="user-dropdown-btn"
                        class="p-1 hover:opacity-70 transition-opacity flex items-center gap-1 focus:outline-none"
                        aria-label="Profile">
                        <svg class="w-5 h-5 text-[#1b1b18]" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span
                            class="hidden sm:inline-block text-[11px] font-bold max-w-[80px] truncate text-[#1b1b18]">{{ Auth::user()->name }}</span>
                        <svg id="user-dropdown-arrow" class="w-3 h-3 text-gray-400 transition-transform duration-200"
                            fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <!-- Dropdown Panel -->
                    <div id="user-dropdown-panel"
                        class="absolute right-0 mt-2 w-48 bg-white border border-[#19140010] rounded-2xl shadow-lg hidden z-50 py-1">
                        <div class="px-4 py-2 border-b border-gray-50">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('account.index') }}"
                            class="block px-4 py-2 text-xs font-semibold transition-colors {{ request()->routeIs('account.index') && !request()->filled('tab') ? 'text-black bg-gray-50' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">My
                            Dashboard</a>
                        <a href="{{ route('account.index', ['tab' => 'orders']) }}"
                            class="block px-4 py-2 text-xs font-semibold transition-colors {{ request()->routeIs('account.index') && request('tab') === 'orders' ? 'text-black bg-gray-50' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">Order
                            History</a>
                        <a href="{{ route('account.index', ['tab' => 'wishlist']) }}"
                            class="block px-4 py-2 text-xs font-semibold transition-colors {{ request()->routeIs('account.index') && request('tab') === 'wishlist' ? 'text-black bg-gray-50' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">Wishlist</a>
                        <a href="{{ route('account.index', ['tab' => 'addresses']) }}"
                            class="block px-4 py-2 text-xs font-semibold transition-colors {{ request()->routeIs('account.index') && request('tab') === 'addresses' ? 'text-black bg-gray-50' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">Saved
                            Addresses</a>

                        <div class="border-t border-gray-50 mt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-xs font-bold text-red-500 hover:bg-red-50/50 transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="p-1 hover:opacity-70 transition-opacity" aria-label="Profile">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </a>
            @endauth
            <button id="cart-trigger-btn" class="p-1 hover:opacity-70 transition-opacity relative"
                aria-label="Shopping Cart">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                @php
                    $cartItCount = auth()->check() ? auth()->user()->cartItems()->count() : 0;
                @endphp

                <span id="cart-count-badge"
                    class="absolute -top-1 -right-1 bg-black text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-bold {{ $cartItCount === 0 ? 'hidden' : '' }}">
                    {{ $cartItCount }}
                </span>
            </button>
            @guest
                <a href="{{ route('login') }}"
                    class="bg-black text-white font-medium text-sm px-4 py-2 rounded-full hover:opacity-70 relative transition-opacity cursor-pointer">Login</a>
            @endguest
        </div>
    </div>
</header>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdownBtn = document.getElementById('user-dropdown-btn');
            const dropdownPanel = document.getElementById('user-dropdown-panel');
            const dropdownArrow = document.getElementById('user-dropdown-arrow');
            const dropdownWrapper = document.getElementById('user-dropdown');

            if (dropdownBtn && dropdownPanel) {
                dropdownBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdownPanel.classList.toggle('hidden');
                    dropdownArrow.classList.toggle('rotate-180');
                });

                // Close when clicking outside
                document.addEventListener('click', (e) => {
                    if (!dropdownWrapper.contains(e.target)) {
                        dropdownPanel.classList.add('hidden');
                        dropdownArrow.classList.remove('rotate-180');
                    }
                });

                // Close on Escape
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        dropdownPanel.classList.add('hidden');
                        dropdownArrow.classList.remove('rotate-180');
                    }
                });
            }
        });
    </script>
@endpush


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const backdrop = document.getElementById('cart-backdrop');
            const sidebar = document.getElementById('cart-sidebar');
            const openBtn = document.getElementById('cart-trigger-btn');
            const closeBtn = document.getElementById('cart-close-btn');

            function openCart() {
                backdrop.classList.remove('hidden');
                requestAnimationFrame(() => {
                    backdrop.classList.remove('opacity-0');
                    sidebar.classList.remove('translate-x-full');
                });
                document.body.classList.add('overflow-hidden');
            }

            function closeCart() {
                backdrop.classList.add('opacity-0');
                sidebar.classList.add('translate-x-full');
                document.body.classList.remove('overflow-hidden');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
            }

            openBtn?.addEventListener('click', openCart);
            closeBtn?.addEventListener('click', closeCart);
            backdrop?.addEventListener('click', closeCart);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !sidebar.classList.contains('translate-x-full')) closeCart();
            });

            // Expose so "Add to Cart" forms elsewhere can open the sidebar after success.
            window.openCartSidebar = openCart;

            // --- Quantity & delete handling (delegated, since items re-render) ---
            async function refreshCartSidebar() {
                const res = await fetch('{{ route('cart.sidebar') }}', {
                    headers: {
                        'Accept': 'text/html'
                    }
                });
                if (!res.ok) return;
                document.getElementById('cart-sidebar').outerHTML = await res.text();
                // Re-bind close/backdrop listeners since the node was replaced.
                document.getElementById('cart-close-btn')?.addEventListener('click', closeCart);
            }

            document.addEventListener('click', async (e) => {
                const qtyBtn = e.target.closest('.cart-qty-btn');
                if (!qtyBtn) return;

                const form = qtyBtn.closest('form');
                const row = qtyBtn.closest('.cart-item-row');
                if (!form || !row) return;

                qtyBtn.disabled = true;

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                    });

                    if (!res.ok) throw new Error('Request failed');
                    const data = await res.json();

                    if (data.removed) {
                        row.remove();
                    } else {
                        row.querySelector('.cart-qty-value').textContent = data.quantity;
                    }

                    document.getElementById('cart-subtotal').textContent = `$${data.subtotal}`;
                    document.getElementById('cart-total').textContent = `$${data.total}`;
                    document.getElementById('cart-delivery').textContent = data.delivery ?
                        `$${data.delivery}` : 'Free';

                    const badge = document.getElementById('cart-count-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.classList.toggle('hidden', data.count === 0);
                    }

                    if (data.count === 0) {
                        // Cleanest way to swap in the "cart is empty" markup correctly.
                        const wrapper = document.getElementById('cart-items-wrapper');
                        if (wrapper) {
                            wrapper.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center text-center py-20">
                        <p class="text-sm font-semibold text-[#1b1b18]">Your cart is empty</p>
                        <p class="text-xs text-gray-400 mt-1">Items you add will show up here.</p>
                    </div>`;
                        }
                    }
                } catch (err) {
                    console.error('Quantity update failed:', err);
                } finally {
                    qtyBtn.disabled = false;
                }
            });
        });
    </script>
@endpush
@push('scripts')
    <script>
        document.addEventListener('submit', async (e) => {
            const form = e.target.closest('.cart-delete-form');
            if (!form) return;

            e.preventDefault();

            const button = form.querySelector('.cart-delete-btn');
            const cartItemRow = form.closest('.cart-item-row');

            if (button.disabled) return;
            button.disabled = true;
            button.textContent = '...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        _method: 'DELETE',
                    }),
                });

                if (!response.ok) {
                    throw new Error('Failed to delete');
                }

                const data = await response.json();

                // Animate removal
                cartItemRow.style.transition = 'all 0.3s ease';
                cartItemRow.style.opacity = '0';
                cartItemRow.style.transform = 'translateX(20px)';

                setTimeout(() => {
                    cartItemRow.remove();

                    // Check if cart is now empty
                    const remainingItems = document.querySelectorAll('.cart-item-row');
                    if (remainingItems.length === 0) {
                        showEmptyCartState();
                    }
                }, 300);

                updateCartTotals(data);
                updateCartBadge(data.cartCount ?? 0);

            } catch (error) {
                console.error('Delete failed:', error);
                button.disabled = false;
                button.textContent = 'Delete';
            }
        });

        function showEmptyCartState() {
            const cartItemsWrapper = document.getElementById('cart-items-wrapper');
            const cartFooter = document.getElementById('cart-footer');

            if (!cartItemsWrapper) return;

            cartItemsWrapper.innerHTML = `
        <div id="cart-empty-state" class="h-full flex flex-col items-center justify-center text-center py-20">
            <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <p class="text-sm font-semibold text-[#1b1b18]">Your cart is empty</p>
            <p class="text-xs text-gray-400 mt-1">Items you add will show up here.</p>
        </div>
    `;

            if (cartFooter) {
                cartFooter.classList.add('hidden');
            }
        }

        function updateCartTotals(data) {
            const subtotalEl = document.getElementById('cart-subtotal');
            const totalEl = document.getElementById('cart-total');

            if (subtotalEl && data.subtotal !== undefined) {
                subtotalEl.textContent = '$' + parseFloat(data.subtotal).toFixed(2);
            }
            if (totalEl && data.total !== undefined) {
                totalEl.textContent = '$' + parseFloat(data.total).toFixed(2);
            }
        }

        function updateCartBadge(count) {
            const badge = document.getElementById('cart-count-badge');
            if (!badge) return;

            badge.textContent = count;
            if (count > 0) {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    </script>
@endpush
