@extends('layout.appLayout')

@section('title', 'Checkout')

@php
    $cartItems =
        $cartItems ??
        (auth()->check()
            ? auth()
                ->user()
                ->cartItems()
                ->with(['product', 'variant'])
                ->get()
            : collect());
    $subtotal = $cartItems->sum->subtotal;
    $delivery = 0;
    $total = $subtotal + $delivery;
    $savedAddresses = auth()->check() ? auth()->user()->addresses()->orderBy('is_default', 'desc')->get() : collect();
@endphp

@section('content')



    <div class="min-h-screen bg-white">
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- Header --}}
            <div class="mb-10">
                <h1 class="text-2xl font-semibold text-[#1b1b18]">Checkout</h1>
                <p class="text-sm text-gray-400 mt-1">Complete your order in just a few steps.</p>
            </div>

            @if ($cartItems->isEmpty())
                <div class="flex flex-col items-center justify-center py-24 text-center">
                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-base font-semibold text-[#1b1b18]">Your cart is empty</p>
                    <p class="text-sm text-gray-400 mt-1">Add some items before checking out.</p>
                    <a href="{{ route('products.index') }}"
                        class="mt-6 inline-block bg-black text-white text-sm font-semibold py-3 px-8 rounded-full hover:bg-black/90 transition-colors">
                        Browse Products
                    </a>
                </div>
            @else
                <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}"
                    class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    @csrf

                    {{-- Left Column: Shipping & Contact --}}
                    <div class="lg:col-span-7 space-y-8">

                        {{-- Contact Information --}}
                        <div class="border border-[#19140010] rounded-2xl p-6">
                            <h2 class="text-sm font-semibold text-[#1b1b18] uppercase tracking-wide mb-5">Contact
                                Information</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label
                                        class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Email</label>
                                    <input type="email" name="email" value="{{ auth()->user()->email ?? old('email') }}"
                                        required
                                        class="w-full h-11 px-4 text-sm text-[#1b1b18] bg-gray-50 border border-[#19140010] rounded-xl focus:outline-none focus:border-[#1b1b18] focus:bg-white transition-all placeholder:text-gray-300"
                                        placeholder="you@example.com">
                                </div>
                                <div class="sm:col-span-2">
                                    <label
                                        class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Phone
                                        Number</label>
                                    <input type="tel" name="phone"
                                        value="{{ auth()->user()->phone_number ?? old('phone') }}" required
                                        class="w-full h-11 px-4 text-sm text-[#1b1b18] bg-gray-50 border border-[#19140010] rounded-xl focus:outline-none focus:border-[#1b1b18] focus:bg-white transition-all placeholder:text-gray-300"
                                        placeholder="+212 6XX XXX XXX">
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Address --}}
                        <div class="border border-[#19140010] rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-5">
                                <h2 class="text-sm font-semibold text-[#1b1b18] uppercase tracking-wide">Shipping Address
                                </h2>
                                @if ($savedAddresses->isNotEmpty())
                                    <span class="text-[11px] text-gray-400">{{ $savedAddresses->count() }} saved</span>
                                @endif
                            </div>

                            {{-- Saved Addresses --}}
                            @if ($savedAddresses->isNotEmpty())
                                <div class="space-y-3 mb-6">
                                    @foreach ($savedAddresses as $address)
                                        <label
                                            class="relative flex items-start gap-3 p-4 rounded-xl border border-[#19140010] cursor-pointer hover:border-[#1b1b18]/30 transition-colors has-[:checked]:border-[#1b1b18] has-[:checked]:bg-gray-50/50">
                                            <input type="radio" name="address_id" value="{{ $address->id }}"
                                                {{ $address->is_default || ($loop->first && !$savedAddresses->contains('is_default', true)) ? 'checked' : '' }}>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="text-sm font-semibold text-[#1b1b18]">{{ $address->full_name }}</span>
                                                    @if ($address->is_default)
                                                        <span
                                                            class="text-[10px] font-bold uppercase tracking-wider text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Default</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 mt-0.5 truncate">
                                                    {{ $address->full_address }}</p>
                                                <p class="text-xs text-gray-400">{{ $address->phone }}</p>
                                            </div>
                                        </label>
                                    @endforeach

                                    <label
                                        class="relative flex items-center gap-3 p-4 rounded-xl border border-dashed border-[#19140010] cursor-pointer hover:border-[#1b1b18]/30 transition-colors has-[:checked]:border-[#1b1b18]">
                                        <input type="radio" name="address_id" value="new" id="address-new"
                                            {{ $savedAddresses->isEmpty() ? 'checked' : '' }}
                                            class="w-4 h-4 text-[#1b1b18] border-gray-300 focus:ring-[#1b1b18]">
                                        <span class="text-sm font-medium text-[#1b1b18]">Use a new address</span>
                                    </label>
                                </div>
                            @endif

                            {{-- New Address Form --}}
                            <div id="new-address-form"
                                class="{{ $savedAddresses->isNotEmpty() ? 'hidden' : '' }} space-y-4">
                                @if ($savedAddresses->isEmpty())
                                    <input type="hidden" name="address_id" value="new">
                                @endif
                                <div>
                                    <label
                                        class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Full
                                        Name</label>
                                    <input type="text" name="full_name"
                                        value="{{ old('full_name', auth()->user()->name ?? '') }}"
                                        class="w-full h-11 px-4 text-sm text-[#1b1b18] bg-gray-50 border border-[#19140010] rounded-xl focus:outline-none focus:border-[#1b1b18] focus:bg-white transition-all placeholder:text-gray-300"
                                        placeholder="John Doe">
                                </div>
                                <div>
                                    <label
                                        class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Street
                                        Address</label>
                                    <input type="text" name="street_address" value="{{ old('street_address') }}"
                                        required
                                        class="w-full h-11 px-4 text-sm text-[#1b1b18] bg-gray-50 border border-[#19140010] rounded-xl focus:outline-none focus:border-[#1b1b18] focus:bg-white transition-all placeholder:text-gray-300"
                                        placeholder="123 Main Street, Apt 4B">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">City</label>
                                        <input type="text" name="city" value="{{ old('city') }}" required
                                            class="w-full h-11 px-4 text-sm text-[#1b1b18] bg-gray-50 border border-[#19140010] rounded-xl focus:outline-none focus:border-[#1b1b18] focus:bg-white transition-all placeholder:text-gray-300"
                                            placeholder="Casablanca">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Postal
                                            Code</label>
                                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" required
                                            class="w-full h-11 px-4 text-sm text-[#1b1b18] bg-gray-50 border border-[#19140010] rounded-xl focus:outline-none focus:border-[#1b1b18] focus:bg-white transition-all placeholder:text-gray-300"
                                            placeholder="20000">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Country</label>
                                    <input type="text" name="country" value="{{ old('country', 'Morocco') }}"
                                        class="w-full h-11 px-4 text-sm text-[#1b1b18] bg-gray-50 border border-[#19140010] rounded-xl focus:outline-none focus:border-[#1b1b18] focus:bg-white transition-all placeholder:text-gray-300">
                                </div>
                                <label class="flex items-center gap-2 mt-2 cursor-pointer">
                                    <input type="checkbox" name="save_address" value="1" checked
                                        class="w-4 h-4 rounded border-gray-300 text-[#1b1b18] focus:ring-[#1b1b18]">
                                    <span class="text-xs text-gray-500">Save this address for next time</span>
                                </label>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="border border-[#19140010] rounded-2xl p-6">
                            <h2 class="text-sm font-semibold text-[#1b1b18] uppercase tracking-wide mb-5">Payment Method
                            </h2>
                            <div class="space-y-3">
                                <label
                                    class="relative flex items-center gap-3 p-4 rounded-xl border border-[#19140010] cursor-pointer hover:border-[#1b1b18]/30 transition-colors has-[:checked]:border-[#1b1b18] has-[:checked]:bg-gray-50/50">
                                    <input type="radio" name="payment_method" value="cod" checked
                                        class="w-4 h-4 text-[#1b1b18] border-gray-300 focus:ring-[#1b1b18]">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-[#1b1b18]">Cash on Delivery</p>
                                            <p class="text-xs text-gray-400">Pay when your order arrives</p>
                                        </div>
                                    </div>
                                </label>

                                <label
                                    class="relative flex items-center gap-3 p-4 rounded-xl border border-[#19140010] cursor-pointer hover:border-[#1b1b18]/30 transition-colors has-[:checked]:border-[#1b1b18] has-[:checked]:bg-gray-50/50 opacity-60">
                                    <input type="radio" name="payment_method" value="card" disabled
                                        class="w-4 h-4 text-[#1b1b18] border-gray-300 focus:ring-[#1b1b18]">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-[#1b1b18]">Credit / Debit Card</p>
                                            <p class="text-xs text-gray-400">Coming soon</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Order Summary --}}
                    <div class="lg:col-span-5">
                        <div class="sticky top-6 border border-[#19140010] rounded-2xl p-6">
                            <h2 class="text-sm font-semibold text-[#1b1b18] uppercase tracking-wide mb-5">Order Summary
                            </h2>

                            {{-- Items List --}}
                            <div class="space-y-4 max-h-80 overflow-y-auto pr-1 mb-6">
                                @foreach ($cartItems as $item)
                                    <div class="flex gap-3">
                                        <div
                                            class="w-14 h-14 rounded-xl bg-gray-50 border border-[#19140010] overflow-hidden shrink-0">
                                            @if ($item->product->image_path)
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($item->product->image_path) }}"
                                                    alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-[#1b1b18] truncate">
                                                {{ $item->product->name }}</p>
                                            <p class="text-[11px] text-gray-400 uppercase tracking-wide mt-0.5">
                                                {{ $item->variant->name ?? ($item->product->category ?? 'Item') }}</p>
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-xs text-gray-400">Qty: {{ $item->quantity }}</span>
                                                <span
                                                    class="text-sm font-bold text-[#1b1b18]">${{ number_format($item->subtotal, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Divider --}}
                            <div class="border-t border-[#19140010] pt-4 space-y-2.5">
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Subtotal</span>
                                    <span class="font-medium text-[#1b1b18]">${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Delivery</span>
                                    <span
                                        class="font-medium text-[#1b1b18]">{{ $delivery > 0 ? '$' . number_format($delivery, 2) : 'Free' }}</span>
                                </div>
                                @if ($delivery > 0)
                                    <div class="flex justify-between text-xs text-gray-500">
                                        <span>Shipping</span>
                                        <span class="font-medium text-[#1b1b18]">${{ number_format($delivery, 2) }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Total --}}
                            <div class="border-t border-[#19140010] mt-4 pt-4 flex justify-between items-center">
                                <span class="text-sm font-bold text-[#1b1b18]">Total</span>
                                <span class="text-lg font-bold text-[#1b1b18]">${{ number_format($total, 2) }}</span>
                            </div>

                            {{-- Submit Button --}}
                            <button type="submit"
                                class="w-full mt-6 bg-black text-white text-sm font-semibold py-3.5 rounded-full hover:bg-black/90 transition-colors flex items-center justify-center gap-2">
                                <span>Place Order</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </button>

                            <a href="{{ route('cart.index') }}"
                                class="block text-center mt-3 text-[11px] text-gray-400 hover:text-[#1b1b18] underline underline-offset-2 transition-colors">
                                Back to Cart
                            </a>

                            {{-- Trust badges --}}
                            <div class="flex items-center justify-center gap-4 mt-5 pt-5 border-t border-[#19140010]">
                                <div class="flex items-center gap-1.5 text-[10px] text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                    </svg>
                                    Secure Checkout
                                </div>
                                <div class="flex items-center gap-1.5 text-[10px] text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                    </svg>
                                    Free Delivery
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newAddressForm = document.getElementById('new-address-form');
            const addressRadios = document.querySelectorAll('input[name="address_id"]');

            function syncAddressForm(selectedValue) {
                if (selectedValue === 'new') {
                    newAddressForm.classList.remove('hidden');
                    newAddressForm.querySelectorAll('input').forEach(input => input.required = true);
                } else {
                    newAddressForm.classList.add('hidden');
                    newAddressForm.querySelectorAll('input').forEach(input => input.required = false);
                }
            }

            addressRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    syncAddressForm(this.value);
                });
            });

            // Run once on load to match whichever radio is checked by default
            const initiallyChecked = document.querySelector('input[name="address_id"]:checked');
            syncAddressForm(initiallyChecked ? initiallyChecked.value : 'new');
        });
    </script>
@endpush
