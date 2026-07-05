@extends('layout.appLayout')

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-12">

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-start gap-3 shadow-sm animate-fade-in"
                id="success-alert">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
                <button onclick="document.getElementById('success-alert').remove()"
                    class="ml-auto text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm" id="error-alert">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="font-medium text-sm">Please correct the errors below:</p>
                        <ul class="mt-1 list-disc list-inside text-xs text-rose-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button onclick="document.getElementById('error-alert').remove()"
                        class="ml-auto text-rose-500 hover:text-rose-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- Page Header -->
        <div class="border-b border-gray-100 pb-8 mb-10">
            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-[#1b1b18]">Account Dashboard</h1>
            <p class="text-gray-500 text-sm mt-1">Hello, {{ $user->name }}. Manage your orders, saved addresses, and
                profile information details here.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-10 items-start">
            <!-- Sidebar Navigation -->
            <aside
                class="w-full lg:w-64 shrink-0 flex lg:flex-col gap-2 overflow-x-auto pb-4 lg:pb-0 border-b lg:border-b-0 lg:border-r border-gray-100">
                <a href="{{ route('account.index', ['tab' => 'orders']) }}"
                    class="tab-btn flex items-center gap-3 px-4 py-3 rounded-[5px] text-sm font-medium text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50/50 transition-all border-b-2 lg:border-b-0 lg:border-l-3 border-transparent {{ $activeTab === 'orders' ? 'active  !border-black bg-[#F3F4F6]' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Order History
                </a>
                <a href="{{ route('account.index', ['tab' => 'wishlist']) }}"
                    class="tab-btn flex items-center gap-3 px-4 py-3 rounded-[5px] text-sm font-medium text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50/50 transition-all border-b-2 lg:border-b-0 lg:border-l-3 border-transparent {{ $activeTab === 'wishlist' ? 'active !border-black bg-[#F3F4F6] ' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    Wishlist & Saved Items
                </a>
                <a href="{{ route('account.index', ['tab' => 'addresses']) }}"
                    class="tab-btn flex items-center gap-3 px-4 py-3 rounded-[5px] text-sm font-medium text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50/50 transition-all border-b-2 lg:border-b-0 lg:border-l-3 border-transparent {{ $activeTab === 'addresses' ? 'active !border-black bg-[#F3F4F6] ' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Saved Addresses
                </a>
                <a href="{{ route('account.index', ['tab' => 'profile']) }}"
                    class="tab-btn flex items-center gap-3 px-4 py-3 rounded-[5px] text-sm font-medium text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50/50 transition-all border-b-2 lg:border-b-0 lg:border-l-3 border-transparent {{ $activeTab === 'profile' ? 'active !border-black bg-[#F3F4F6] ' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Personal Info
                </a>

                <hr class="hidden lg:block border-gray-100 my-4" />

                <form method="POST" action="{{ route('logout') }}" class="hidden lg:block">
                    @csrf
                    <button type="submit"
                        class="w-full text-left flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50/50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </aside>

            <!-- Active Tab Content Area -->
            <main class="flex-grow w-full">

                <!-- TAB: ORDER HISTORY -->
                <div class="{{ $activeTab === 'orders' ? '' : 'hidden' }}">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-[#1b1b18]">Order History</h2>
                        <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full">{{ $orders->count() }}
                            placed {{ Str::plural('order', $orders->count()) }}</span>
                    </div>

                    @if ($orders->isEmpty())
                        <div class="border border-dashed border-gray-200 rounded-3xl p-16 text-center">
                            <div
                                class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-[#1b1b18] mb-1">No orders yet</h3>
                            <p class="text-gray-400 text-xs max-w-xs mx-auto mb-6">When you purchase your frames, your
                                historical order details will appear right here.</p>
                            <a href="{{ url('/') }}#products"
                                class="inline-flex items-center justify-center px-6 py-2.5 bg-black text-white rounded-full text-xs font-medium hover:bg-black/90 transition-colors">
                                Browse Collection
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($orders as $order)
                                <div
                                    class="bg-white border border-[#19140010] rounded-2xl p-6 transition-all hover:shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2.5">
                                            <span
                                                class="text-xs font-semibold tracking-wider text-gray-400 uppercase">#{{ $order->order_number }}</span>
                                            <span class="text-xs text-gray-500">•</span>
                                            <span
                                                class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-3 mt-1.5">
                                            <!-- Status badge -->
                                            @if ($order->status === 'processing')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                                    <span
                                                        class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                                    Processing
                                                </span>
                                            @elseif ($order->status === 'shipped')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-sky-50 text-sky-800 border border-sky-200">
                                                    <span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span> Shipped
                                                </span>
                                            @elseif ($order->status === 'delivered')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Delivered
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200">
                                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Cancelled
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center justify-between w-full md:w-auto md:justify-end gap-6 border-t md:border-0 border-gray-50 pt-4 md:pt-0">
                                        <div class="text-right">
                                            <span
                                                class="text-xs text-gray-400 block uppercase tracking-wider font-medium">Total</span>
                                            <span
                                                class="text-base font-bold text-[#1b1b18]">${{ number_format($order->total_amount, 2) }}</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            @if ($order->status !== 'cancelled')
                                                <a href="{{ route('account.orders.tracking', $order->id) }}"
                                                    class="inline-flex items-center justify-center px-5 py-2 bg-black text-white rounded-full text-xs font-semibold hover:bg-black/90 transition-colors">
                                                    Order Details And Tracking
                                                </a>
                                            @endif

                                            {{--    <button
                                                class="view-order-details-btn inline-flex items-center justify-center px-5 py-2 border border-gray-200 rounded-full text-xs font-semibold hover:bg-gray-50 text-black transition-colors"
                                                data-order="{{ json_encode($order->load('items')) }}">
                                                Details
                                            </button> --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- TAB: WISHLIST -->
                <div class="{{ $activeTab === 'wishlist' ? '' : 'hidden' }}">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-[#1b1b18]">Wishlist & Saved Items</h2>
                        <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full">{{ $wishlist->count() }}
                            items saved</span>
                    </div>

                    @if ($wishlist->isEmpty())
                        <div class="border border-dashed border-gray-200 rounded-3xl p-16 text-center">
                            <div
                                class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-[#1b1b18] mb-1">Your wishlist is empty</h3>
                            <p class="text-gray-400 text-xs max-w-xs mx-auto mb-6">Browse and save glasses from the store
                                to keep an eye on your favorites.</p>
                            <a href="{{ url('/') }}#products"
                                class="inline-flex items-center justify-center px-6 py-2.5 bg-black text-white rounded-full text-xs font-medium hover:bg-black/90 transition-colors">
                                Explore Products
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($wishlist as $product)
                                <div
                                    class="group border border-[#19140010] bg-white rounded-2xl p-6 transition-all duration-300 hover:shadow-lg relative flex flex-col h-full">

                                    <!-- Remove button -->
                                    <form method="POST" action="{{ route('account.wishlist.toggle') }}"
                                        class="absolute top-4 right-4 z-10">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit"
                                            class="p-2 bg-white/80 hover:bg-white text-gray-400 hover:text-red-500 rounded-full border border-gray-100 shadow-sm transition-all focus:outline-none"
                                            aria-label="Remove from Wishlist">
                                            <svg class="w-4 h-4 fill-red-500 text-red-500" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- Product Details -->
                                    <div
                                        class="aspect-square bg-gray-50 rounded-xl overflow-hidden mb-6 flex items-center justify-center p-4 relative shrink-0">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                            class="max-h-full object-contain transition-transform duration-500 group-hover:scale-108">
                                    </div>

                                    <div class="flex-grow flex flex-col justify-between">
                                        <div>
                                            <h3
                                                class="text-base font-semibold text-center group-hover:text-black transition-colors mb-1">
                                                {{ $product->name }}</h3>
                                            <p
                                                class="text-xs text-[#706f6c] text-center leading-relaxed max-w-[210px] mx-auto mb-4">
                                                {{ $product->description }}
                                            </p>
                                        </div>

                                        <div
                                            class="border-t border-gray-50 pt-4 flex items-center justify-between mt-auto">
                                            <span
                                                class="text-sm font-bold">${{ number_format($product->price, 2) }}</span>

                                            <!-- Simple buy simulation -->
                                            <form method="POST" action="#" class="simulated-purchase-form"
                                                data-product-id="{{ $product->id }}"
                                                data-product-price="{{ $product->price }}"
                                                data-product-name="{{ $product->name }}">
                                                @csrf
                                                <button type="button"
                                                    class="buy-now-simulation-btn text-xs font-semibold px-4 py-2 bg-[#0b0b0a] text-white rounded-full hover:bg-black/80 transition-colors">
                                                    Order Now
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- TAB: SAVED ADDRESSES -->
                <div class="{{ $activeTab === 'addresses' ? '' : 'hidden' }}">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-[#1b1b18]">Saved Addresses</h2>
                        <button id="add-address-btn"
                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-black rounded-full text-xs font-semibold hover:bg-black hover:text-white transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add Address
                        </button>
                    </div>

                    @if ($addresses->isEmpty())
                        <div class="border border-dashed border-gray-200 rounded-3xl p-16 text-center">
                            <div
                                class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-[#1b1b18] mb-1">No saved addresses</h3>
                            <p class="text-gray-400 text-xs max-w-xs mx-auto mb-6">Save your delivery addresses for a
                                faster, one-click checkout experience.</p>
                            <button onclick="document.getElementById('add-address-btn').click()"
                                class="inline-flex items-center justify-center px-6 py-2.5 bg-black text-white rounded-full text-xs font-medium hover:bg-black/90 transition-colors">
                                Add First Address
                            </button>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($addresses as $address)
                                <div
                                    class="bg-white border {{ $address->is_default ? 'border-black' : 'border-[#19140010]' }} rounded-2xl p-6 relative flex flex-col justify-between hover:shadow-md transition-shadow">

                                    @if ($address->is_default)
                                        <span
                                            class="absolute top-4 right-4 bg-black text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Default</span>
                                    @endif

                                    <div class="space-y-3">
                                        <div>
                                            <p class="font-bold text-[#1b1b18] text-base">{{ $address->full_name }}</p>
                                            <p class="text-gray-400 text-xs mt-0.5">{{ $address->phone }}</p>
                                        </div>

                                        <div class="text-sm text-gray-600 leading-relaxed">
                                            <p>{{ $address->street_address }}</p>
                                            <p>{{ $address->city }}, {{ $address->postal_code }}</p>
                                            <p class="font-medium text-[#1b1b18] mt-1">{{ $address->country }}</p>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-50 pt-4 mt-6 flex items-center justify-between">
                                        <!-- Actions -->
                                        <div class="flex items-center gap-3">
                                            <button
                                                class="edit-address-btn text-xs text-gray-500 hover:text-black font-semibold transition-colors focus:outline-none"
                                                data-address="{{ json_encode($address) }}">
                                                Edit
                                            </button>

                                            <form method="POST"
                                                action="{{ route('account.addresses.destroy', $address->id) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this address?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-xs text-gray-400 hover:text-red-600 font-semibold transition-colors focus:outline-none">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>

                                        @if (!$address->is_default)
                                            <form method="POST"
                                                action="{{ route('account.addresses.default', $address->id) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="text-xs font-bold text-gray-900 border-b border-black hover:opacity-75 pb-0.5 focus:outline-none">
                                                    Set default
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="{{ $activeTab === 'profile' ? '' : 'hidden' }}">
                    <div class="space-y-10">

                        <!-- Details Edit -->
                        <div class="bg-white border border-[#19140010] rounded-3xl p-6 sm:p-8">
                            <h2 class="text-lg font-semibold text-[#1b1b18] mb-1">Personal Details</h2>
                            <p class="text-gray-400 text-xs mb-6">Update your personal account identifiers.</p>

                            <form method="POST" action="{{ route('account.profile.update') }}"
                                class="space-y-5 max-w-xl">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="name"
                                        class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">Full
                                        Name</label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required
                                        class="mt-2 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors">
                                </div>

                                <div>
                                    <label for="email"
                                        class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">Email
                                        Address</label>
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" required
                                        class="mt-2 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors">
                                </div>
                                <div>
                                    <label for="phone_number"
                                        class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">Phone
                                        Number</label>
                                    <input type="text" id="phone_number" name="phone_number"
                                        value="{{ old('phone_number', $user->phone_number) }}" required
                                        class="mt-2 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors">
                                </div>

                                <div class="pt-2">
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-6 py-2.5 bg-black text-white rounded-full text-xs font-semibold hover:bg-black/90 transition-colors">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Password Update -->
                        <div class="bg-white border border-[#19140010] rounded-3xl p-6 sm:p-8">
                            <h2 class="text-lg font-semibold text-[#1b1b18] mb-1">Change Password</h2>
                            <p class="text-gray-400 text-xs mb-6">Ensure your account is using a long, random password to
                                stay secure.</p>

                            <form method="POST" action="{{ route('account.password.update') }}"
                                class="space-y-5 max-w-xl">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="current_password"
                                        class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">Current
                                        Password</label>
                                    <input type="password" id="current_password" name="current_password" required
                                        class="mt-2 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors"
                                        placeholder="••••••••">
                                </div>

                                <div>
                                    <label for="password"
                                        class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">New
                                        Password</label>
                                    <input type="password" id="password" name="password" required
                                        class="mt-2 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors"
                                        placeholder="Minimum 8 characters">
                                </div>

                                <div>
                                    <label for="password_confirmation"
                                        class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">Confirm New
                                        Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        required
                                        class="mt-2 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors"
                                        placeholder="••••••••">
                                </div>

                                <div class="pt-2">
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-6 py-2.5 bg-black text-white rounded-full text-xs font-semibold hover:bg-black/90 transition-colors">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- ORDER DETAIL MODAL -->
    <div id="order-details-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background Overlay -->
            <div id="order-modal-overlay" class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-xs"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Content Panel -->
            <div
                class="relative z-10 inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-6 pt-6 pb-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-[#1b1b18]" id="modal-order-number">Order Details</h3>
                        <p class="text-xs text-gray-400 mt-0.5" id="modal-order-date">Date</p>
                    </div>
                    <button id="close-order-modal-btn"
                        class="p-2 text-gray-400 hover:text-black hover:bg-gray-50 rounded-full transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-6 max-h-[60vh] overflow-y-auto">
                    <!-- Status & Tracking -->
                    <div
                        class="bg-gray-50 border border-gray-100 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Order Status</span>
                            <div class="mt-1" id="modal-order-status-badge">Status</div>
                        </div>
                        <div id="modal-tracking-wrapper" class="hidden">
                            <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Tracking
                                Link</span>
                            <div class="mt-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10M13 16h3.586a1 1 0 00.707-.293l2.414-2.414a1 1 0 00.293-.707V11h-7v5z" />
                                </svg>
                                <a id="modal-tracking-link" href="#" target="_blank"
                                    class="text-xs text-sky-600 font-bold hover:underline">Track Order</a>
                            </div>
                        </div>
                    </div>

                    <!-- Items Ordered -->
                    <div class="mb-6">
                        <h4 class="text-xs font-bold text-[#1b1b18] uppercase tracking-wider mb-3">Items Ordered</h4>
                        <div class="divide-y divide-gray-100 border-y border-gray-100" id="modal-items-container">
                            <!-- Items dynamically injected here -->
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div>
                        <h4 class="text-xs font-bold text-[#1b1b18] uppercase tracking-wider mb-2">Delivery Address</h4>
                        <div class="bg-white border border-[#19140008] p-4 rounded-xl text-sm leading-relaxed text-gray-600"
                            id="modal-address-container">
                            <!-- Address injected here -->
                        </div>
                    </div>
                </div>

                <!-- Footer Summary -->
                <div class="bg-gray-50 border-t border-gray-100 px-6 py-6 flex flex-col gap-2">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Subtotal</span>
                        <span id="modal-summary-subtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Shipping</span>
                        <span class="text-emerald-600 font-medium">Free</span>
                    </div>
                    <div
                        class="flex justify-between text-sm font-bold text-[#1b1b18] border-t border-gray-200/50 pt-2 mt-1">
                        <span>Total Paid</span>
                        <span id="modal-summary-total">$0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADDRESS MODAL (ADD & EDIT) -->
    <div id="address-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">

        <!-- Overlay -->
        <div id="address-modal-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

        <!-- Content -->
        <div class="fixed inset-0 z-10 flex items-center justify-center px-4 py-6 overflow-y-auto">
            <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl">

                <div class="bg-white px-6 pt-6 pb-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-[#1b1b18]" id="address-modal-title">Add Address</h3>
                    <button id="close-address-modal-btn"
                        class="p-2 text-gray-400 hover:text-black hover:bg-gray-50 rounded-full transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="address-form" method="POST" action="{{ route('account.addresses.store') }}"
                    class="px-6 py-6 space-y-4">
                    @csrf
                    <!-- Dynamic Method Injection for PUT -->
                    <div id="address-method-container"></div>

                    <div>
                        <label for="full_name"
                            class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required
                            class="mt-1 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors"
                            placeholder="e.g. John Doe">
                    </div>

                    <div>
                        <label for="phone"
                            class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required
                            class="mt-1 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors"
                            placeholder="e.g. +212 6XX XXX XXX">
                    </div>

                    <div>
                        <label for="street_address"
                            class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">Street Address</label>
                        <input type="text" id="street_address" name="street_address" required
                            class="mt-1 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors"
                            placeholder="123 Main Street, Apt 4B">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="city"
                                class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">City</label>
                            <input type="text" id="city" name="city" required
                                class="mt-1 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors"
                                placeholder="e.g. Casablanca">
                        </div>
                        <div>
                            <label for="postal_code"
                                class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" required
                                class="mt-1 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors"
                                placeholder="e.g. 20000">
                        </div>
                    </div>

                    <div>
                        <label for="country"
                            class="block text-xs font-bold text-[#1b1b18] uppercase tracking-wider">Country</label>
                        <input type="text" id="country" name="country" required
                            class="mt-1 block w-full border-0 border-b border-gray-200 bg-transparent px-1 py-2 text-sm text-[#1b1b18] focus:border-black focus:ring-0 transition-colors"
                            placeholder="e.g. Morocco">
                    </div>

                    <div class="flex items-center gap-2 pt-2 cursor-pointer select-none">
                        <input type="checkbox" id="is_default" name="is_default" value="1"
                            class="rounded border-gray-300 text-black focus:ring-black">
                        <label for="is_default" class="text-xs text-gray-500 font-medium cursor-pointer">Set as default
                            delivery address</label>
                    </div>

                    <div class="border-t border-gray-100 pt-5 flex items-center justify-end gap-3 mt-6">
                        <button type="button" id="cancel-address-btn"
                            class="px-5 py-2.5 border border-gray-200 rounded-full text-xs font-semibold hover:bg-gray-50 text-black transition-colors focus:outline-none">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-black text-white rounded-full text-xs font-semibold hover:bg-black/90 transition-colors focus:outline-none">
                            Save Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MOCK SIMULATED ORDER MODAL (SIMULATION POPUP) -->
    <div id="simulation-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background Overlay -->
            <div id="simulation-modal-overlay" class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-xs">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Content Panel -->
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-6 pt-6 pb-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-[#1b1b18]">Simulate Order Placement</h3>
                    <button id="close-simulation-btn"
                        class="p-2 text-gray-400 hover:text-black hover:bg-gray-50 rounded-full transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-6 text-sm text-gray-600">
                    <p class="mb-4">You are about to simulate purchasing the <strong id="simulated-product-name"
                            class="text-black">Nova</strong> glasses for <strong id="simulated-product-price"
                            class="text-black">$149.00</strong>.</p>

                    @if ($addresses->isEmpty())
                        <div
                            class="p-3.5 bg-amber-50 text-amber-800 border border-amber-200 rounded-xl text-xs flex gap-2.5">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="font-bold">No saved addresses</p>
                                <p class="mt-0.5 leading-relaxed">Please add a shipping address in your "Saved Addresses"
                                    tab before simulating an order placement.</p>
                            </div>
                        </div>
                    @else
                        <div class="space-y-3">
                            <label for="simulation_address_id"
                                class="block text-xs font-bold text-gray-900 uppercase tracking-wider">Select Shipping
                                Address</label>
                            <select id="simulation_address_id"
                                class="w-full border-gray-200 rounded-xl text-sm p-3 focus:border-black focus:ring-0">
                                @foreach ($addresses as $address)
                                    <option value="{{ $address->id }}">
                                        {{ $address->recipient_name }} — {{ $address->address_line1 }},
                                        {{ $address->city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3 rounded-b-3xl">
                    <button type="button" id="cancel-simulation-btn"
                        class="px-5 py-2.5 border border-gray-200 rounded-full text-xs font-semibold hover:bg-gray-50 text-black transition-colors focus:outline-none">
                        Cancel
                    </button>
                    @if (!$addresses->isEmpty())
                        <button type="button" id="confirm-simulation-btn"
                            class="px-6 py-2.5 bg-black text-white rounded-full text-xs font-semibold hover:bg-black/90 transition-colors focus:outline-none">
                            Place Order
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JS Scripts for Modals & Interactive Behaviour -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- 1. Order Detail Modal Logic ---
            const orderModal = document.getElementById('order-details-modal');
            const closeOrderModalBtn = document.getElementById('close-order-modal-btn');
            const orderModalOverlay = document.getElementById('order-modal-overlay');

            const viewOrderDetailsBtns = document.querySelectorAll('.view-order-details-btn');

            const modalOrderNumber = document.getElementById('modal-order-number');
            const modalOrderDate = document.getElementById('modal-order-date');
            const modalOrderStatusBadge = document.getElementById('modal-order-status-badge');
            const modalTrackingWrapper = document.getElementById('modal-tracking-wrapper');
            const modalTrackingLink = document.getElementById('modal-tracking-link');
            const modalItemsContainer = document.getElementById('modal-items-container');
            const modalAddressContainer = document.getElementById('modal-address-container');
            const modalSummarySubtotal = document.getElementById('modal-summary-subtotal');
            const modalSummaryTotal = document.getElementById('modal-summary-total');

            const openOrderModal = (orderData) => {
                modalOrderNumber.textContent = `Order #${orderData.order_number}`;

                // Format Date
                const date = new Date(orderData.created_at);
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                modalOrderDate.textContent = `Placed on ${date.toLocaleDateString('en-US', options)}`;

                // Set Status Badge
                let statusBadgeHtml = '';
                if (orderData.status === 'processing') {
                    statusBadgeHtml =
                        `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Processing</span>`;
                } else if (orderData.status === 'shipped') {
                    statusBadgeHtml =
                        `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-sky-50 text-sky-800 border border-sky-200"><span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span> Shipped</span>`;
                } else if (orderData.status === 'delivered') {
                    statusBadgeHtml =
                        `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Delivered</span>`;
                } else {
                    statusBadgeHtml =
                        `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200"><span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Cancelled</span>`;
                }
                modalOrderStatusBadge.innerHTML = statusBadgeHtml;

                // Set Tracking
                if (orderData.tracking_code) {
                    modalTrackingWrapper.classList.remove('hidden');
                    modalTrackingLink.textContent = `Track Order (${orderData.tracking_code})`;
                    // Mock tracking link redirect
                    modalTrackingLink.href =
                        `https://www.fedex.com/fedextrack/?tracknumbers=${orderData.tracking_code}`;
                } else {
                    modalTrackingWrapper.classList.add('hidden');
                }

                // Set Delivery Address
                modalAddressContainer.innerHTML = orderData.delivery_address.replace(/\n/g, '<br>');

                // Render Items
                modalItemsContainer.innerHTML = '';
                let calculatedSubtotal = 0;

                orderData.items.forEach(item => {
                    const itemSubtotal = parseFloat(item.price) * parseInt(item.quantity);
                    calculatedSubtotal += itemSubtotal;
                    console.log("this is item", item);

                    const itemHtml = `
                        <div class="py-4 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-50 border border-gray-100 rounded-lg p-1 flex items-center justify-center shrink-0">
                                    <img src="${item.image_url}" alt="${item.product_name}" class="max-h-full object-contain">
                                </div>
                                <div>
                                    <h5 class="text-xs font-semibold text-[#1b1b18]">${item.product_name}</h5>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Qty ${item.quantity} @ $${parseFloat(item.price).toFixed(2)}</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-[#1b1b18]">$${itemSubtotal.toFixed(2)}</span>
                        </div>
                    `;
                    modalItemsContainer.insertAdjacentHTML('beforeend', itemHtml);
                });

                // Update Summary Totals
                modalSummarySubtotal.textContent = `$${calculatedSubtotal.toFixed(2)}`;
                modalSummaryTotal.textContent = `$${parseFloat(orderData.total_amount).toFixed(2)}`;

                // Open modal
                orderModal.classList.remove('hidden');
                document.body.classList.add('modal-active');
            };

            const closeOrderModal = () => {
                orderModal.classList.add('hidden');
                document.body.classList.remove('modal-active');
            };

            viewOrderDetailsBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const orderData = JSON.parse(btn.dataset.order);
                    openOrderModal(orderData);
                });
            });

            if (closeOrderModalBtn) {
                closeOrderModalBtn.addEventListener('click', closeOrderModal);
            }
            if (orderModalOverlay) {
                orderModalOverlay.addEventListener('click', closeOrderModal);
            }


            // --- 2. Address Book Add/Edit Modal Logic ---
            const addressModal = document.getElementById('address-modal');
            const addAddressBtn = document.getElementById('add-address-btn');
            const closeAddressModalBtn = document.getElementById('close-address-modal-btn');
            const cancelAddressBtn = document.getElementById('cancel-address-btn');
            const addressModalOverlay = document.getElementById('address-modal-overlay');

            const addressForm = document.getElementById('address-form');
            const addressModalTitle = document.getElementById('address-modal-title');
            const addressMethodContainer = document.getElementById('address-method-container');

            // Address Fields
            const recipientNameInput = document.getElementById('recipient_name');
            const phoneInput = document.getElementById('phone');
            const addressLine1Input = document.getElementById('address_line1');
            const addressLine2Input = document.getElementById('address_line2');
            const cityInput = document.getElementById('city');
            const stateInput = document.getElementById('state');
            const postalCodeInput = document.getElementById('postal_code');
            const countryInput = document.getElementById('country');
            const isDefaultInput = document.getElementById('is_default');

            const openAddressModal = (addressData = null) => {
                if (addressData) {
                    // EDIT MODE
                    addressModalTitle.textContent = 'Edit Address';
                    addressForm.action = `/account/addresses/${addressData.id}`;
                    addressMethodContainer.innerHTML = '@method('PUT')';

                    // Fill inputs
                    recipientNameInput.value = addressData.recipient_name;
                    phoneInput.value = addressData.phone;
                    addressLine1Input.value = addressData.address_line1;
                    addressLine2Input.value = addressData.address_line2 || '';
                    cityInput.value = addressData.city;
                    stateInput.value = addressData.state || '';
                    postalCodeInput.value = addressData.postal_code;
                    countryInput.value = addressData.country;
                    isDefaultInput.checked = addressData.is_default === 1 || addressData.is_default === true;
                } else {
                    // ADD MODE
                    addressModalTitle.textContent = 'Add Address';
                    addressForm.action = "{{ route('account.addresses.store') }}";
                    addressMethodContainer.innerHTML = '';

                    // Reset form fields
                    addressForm.reset();
                    isDefaultInput.checked = false;
                }

                addressModal.classList.remove('hidden');
                document.body.classList.add('modal-active');
            };

            const closeAddressModal = () => {
                addressModal.classList.add('hidden');
                document.body.classList.remove('modal-active');
            };

            if (addAddressBtn) {
                addAddressBtn.addEventListener('click', () => openAddressModal());
            }

            const editAddressBtns = document.querySelectorAll('.edit-address-btn');
            editAddressBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const addressData = JSON.parse(btn.dataset.address);
                    openAddressModal(addressData);
                });
            });

            if (closeAddressModalBtn) closeAddressModalBtn.addEventListener('click', closeAddressModal);
            if (cancelAddressBtn) cancelAddressBtn.addEventListener('click', closeAddressModal);
            if (addressModalOverlay) addressModalOverlay.addEventListener('click', closeAddressModal);


            // --- 3. Simulated Checkout Order Simulation ---
            const simulationModal = document.getElementById('simulation-modal');
            const simulatedProdName = document.getElementById('simulated-product-name');
            const simulatedProdPrice = document.getElementById('simulated-product-price');
            const confirmSimBtn = document.getElementById('confirm-simulation-btn');
            const cancelSimBtn = document.getElementById('cancel-simulation-btn');
            const closeSimBtn = document.getElementById('close-simulation-btn');
            const simulationModalOverlay = document.getElementById('simulation-modal-overlay');
            const simulationAddressSelect = document.getElementById('simulation_address_id');

            let selectedSimProduct = null;

            const buyNowBtns = document.querySelectorAll('.buy-now-simulation-btn');
            buyNowBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const form = btn.closest('.simulated-purchase-form');
                    selectedSimProduct = {
                        id: form.dataset.productId,
                        name: form.dataset.productName,
                        price: parseFloat(form.dataset.productPrice)
                    };

                    simulatedProdName.textContent = selectedSimProduct.name;
                    simulatedProdPrice.textContent = `$${selectedSimProduct.price.toFixed(2)}`;

                    simulationModal.classList.remove('hidden');
                    document.body.classList.add('modal-active');
                });
            });

            const closeSimulationModal = () => {
                simulationModal.classList.add('hidden');
                document.body.classList.remove('modal-active');
            };

            if (closeSimBtn) closeSimBtn.addEventListener('click', closeSimulationModal);
            if (cancelSimBtn) cancelSimBtn.addEventListener('click', closeSimulationModal);
            if (simulationModalOverlay) simulationModalOverlay.addEventListener('click', closeSimulationModal);

            if (confirmSimBtn) {
                confirmSimBtn.addEventListener('click', () => {
                    if (!selectedSimProduct) return;

                    const addressId = simulationAddressSelect ? simulationAddressSelect.value : null;
                    if (!addressId) {
                        alert('Please select or add a shipping address.');
                        return;
                    }

                    confirmSimBtn.disabled = true;
                    confirmSimBtn.textContent = 'Placing Order...';

                    // Create simulated order via API AJAX endpoint
                    // Let's create an endpoint in web.php / app. We'll add a route specifically to handle this mockup order simulation.
                    axios.post('/account/orders/simulate', {
                            product_id: selectedSimProduct.id,
                            address_id: addressId
                        })
                        .then(response => {
                            closeSimulationModal();
                            // Redirect to orders tab with success
                            window.location.href =
                                "{{ route('account.index', ['tab' => 'orders']) }}&success=Order placed successfully!";
                        })
                        .catch(error => {
                            console.error(error);
                            alert(error.response?.data?.message || 'Error occurred during simulation.');
                            confirmSimBtn.disabled = false;
                            confirmSimBtn.textContent = 'Place Order';
                        });
                });
            }

            // Close modal on Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeOrderModal();
                    closeAddressModal();
                    closeSimulationModal();
                }
            });
        });
    </script>

@endsection
