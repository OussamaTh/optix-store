@extends('layout.appLayout')

@section('content')
    <div class="max-w-4xl mx-auto px-6 py-12">

        <!-- Back link -->
        <a href="{{ route('account.index', ['tab' => 'orders']) }}"
            class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-black transition-colors mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Order History
        </a>

        <!-- Page Header -->
        <div class="border-b border-gray-100 pb-8 mb-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <span class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Order
                    #{{ $order->order_number }}</span>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-[#1b1b18] mt-1">Order Tracking</h1>
                <p class="text-gray-500 text-sm mt-1">Placed on {{ $order->created_at->format('F d, Y') }}</p>
            </div>

            <!-- Status badge -->
            @if ($order->status === 'processing')
                <span
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200 w-fit">
                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Processing
                </span>
            @elseif ($order->status === 'shipped')
                <span
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-medium bg-sky-50 text-sky-800 border border-sky-200 w-fit">
                    <span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span> Shipped
                </span>
            @elseif ($order->status === 'delivered')
                <span
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200 w-fit">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Delivered
                </span>
            @else
                <span
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200 w-fit">
                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Cancelled
                </span>
            @endif
        </div>

        <!-- Tracking Timeline -->
        <div class="bg-white border border-[#19140010] rounded-3xl p-6 sm:p-10 mb-8">
            @if ($order->status === 'cancelled')
                <div class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-2xl">
                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <p class="text-sm text-gray-500 font-medium">This order was cancelled and is no longer being tracked.
                    </p>
                </div>
            @else
                @php
                    $steps = [
                        'placed' => 'Order Placed',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                    ];
                    $statusOrder = array_keys($steps);
                    $currentIndex = array_search(
                        in_array($order->status, $statusOrder) ? $order->status : 'placed',
                        $statusOrder,
                    );
                @endphp

                <div class="flex items-start">
                    @foreach ($steps as $key => $label)
                        @php
                            $idx = array_search($key, $statusOrder);
                            $isComplete = $idx <= $currentIndex;
                            $isCurrent = $idx === $currentIndex;
                            $isLast = $idx === count($steps) - 1;
                        @endphp
                        <div class="flex flex-col items-center {{ $isLast ? '' : 'flex-1' }}">
                            <div class="flex items-center w-full">
                                <div
                                    class="relative flex items-center justify-center w-10 h-10 rounded-full shrink-0 transition-colors
                                {{ $isComplete ? 'bg-black text-white' : 'bg-gray-100 text-gray-400' }}
                                {{ $isCurrent ? 'ring-4 ring-gray-100' : '' }}">
                                    @if ($isComplete && !$isCurrent)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <span class="text-sm font-bold">{{ $idx + 1 }}</span>
                                    @endif
                                </div>
                                @if (!$isLast)
                                    <div
                                        class="flex-1 h-0.5 mx-1 {{ $idx < $currentIndex ? 'bg-black' : 'bg-gray-100' }} transition-colors">
                                    </div>
                                @endif
                            </div>
                            <span
                                class="text-xs font-semibold mt-3 text-center px-1 {{ $isComplete ? 'text-[#1b1b18]' : 'text-gray-400' }} {{ $isCurrent ? 'font-bold' : '' }}">
                                {{ $label }}
                            </span>
                        </div>
                    @endforeach
                </div>

                @if ($order->tracking_code)
                    <div
                        class="mt-10 pt-6 border-t border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Tracking
                                Number</span>
                            <span
                                class="text-sm font-semibold text-[#1b1b18] mt-0.5 block">{{ $order->tracking_code }}</span>
                        </div>
                        <a href="https://www.fedex.com/fedextrack/?tracknumbers={{ $order->tracking_code }}"
                            target="_blank"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-black text-white rounded-full text-xs font-semibold hover:bg-black/90 transition-colors w-fit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10M13 16h3.586a1 1 0 00.707-.293l2.414-2.414a1 1 0 00.293-.707V11h-7v5z" />
                            </svg>
                            Track with Carrier
                        </a>
                    </div>
                @endif
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Items Ordered -->
            <div class="lg:col-span-2 bg-white border border-[#19140010] rounded-3xl p-6 sm:p-8">
                <h2 class="text-sm font-bold text-[#1b1b18] uppercase tracking-wider mb-4">Items Ordered</h2>
                <div class="divide-y divide-gray-100 border-y border-gray-100">
                    @foreach ($order->items as $item)
                        @php $itemSubtotal = $item->price * $item->quantity; @endphp
                        <div class="py-4 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-14 h-14 bg-gray-50 border border-gray-100 rounded-lg p-1.5 flex items-center justify-center shrink-0">
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}"
                                        class="max-h-full object-contain">
                                </div>
                                <div>
                                    <h5 class="text-sm font-semibold text-[#1b1b18]">{{ $item->product_name }}</h5>
                                    @if ($item->variant_name)
                                        <p class="text-[11px] text-gray-400 uppercase tracking-wide mt-0.5">
                                            {{ $item->variant_name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-0.5">Qty {{ $item->quantity }} @
                                        {{ number_format($item->price, 2) }} DH</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-[#1b1b18]">{{ number_format($itemSubtotal, 2) }} DH</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sidebar: Address + Summary -->
            <div class="space-y-6">
                <div class="bg-white border border-[#19140010] rounded-3xl p-6">
                    <h2 class="text-sm font-bold text-[#1b1b18] uppercase tracking-wider mb-3">Delivery Address</h2>

                    <div class="space-y-1.5">
                        {{-- Recipient Name --}}
                        <p class="text-sm font-semibold text-[#1b1b18]">
                            {{ $order->address->full_name }}
                        </p>

                        {{-- Street Address (multiline support) --}}
                        <div class="text-sm text-gray-600 leading-relaxed">
                            {!! nl2br(e($order->address->street_address)) !!}
                        </div>

                        {{-- City & Postal Code --}}
                        <p class="text-sm text-gray-600">
                            {{ $order->address->city }}, {{ $order->address->postal_code }}
                        </p>

                        {{-- Country --}}
                        <p class="text-sm text-gray-600">
                            {{ $order->address->country }}
                        </p>

                        {{-- Phone --}}
                        <div class="flex items-center gap-1.5 mt-2 pt-2 border-t border-[#19140010]">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            <span class="text-xs text-gray-500">{{ $order->address->phone }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-3xl p-6">
                    <h2 class="text-sm font-bold text-[#1b1b18] uppercase tracking-wider mb-3">Order Summary</h2>
                    <div class="flex justify-between text-xs text-gray-500 mb-2">
                        <span>Subtotal</span>
                        <span>{{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 2) }} DH</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mb-2">
                        <span>Shipping</span>
                        <span class="text-emerald-600 font-medium">Free</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-[#1b1b18] border-t border-gray-200 pt-2 mt-1">
                        <span>Total Paid</span>
                        <span>{{ number_format($order->total_amount, 2) }} DH</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
