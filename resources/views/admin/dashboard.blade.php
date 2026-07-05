@extends('appAdminLayout.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    @php
        // ---- Demo fallbacks so the view never breaks if a key is missing.
        // Replace these with real data from DashboardController.
        $revenueTrend = $stats['revenue_trend'] ?? 12.4; // % vs last period
        $ordersTrend = $stats['orders_trend'] ?? 8.1;
        $pendingTrend = $stats['pending_trend'] ?? -3.2;
        $productsTrend = $stats['products_trend'] ?? 2.0;

        $salesChart = $salesChart ?? [
            ['label' => 'Mon', 'value' => 42],
            ['label' => 'Tue', 'value' => 58],
            ['label' => 'Wed', 'value' => 39],
            ['label' => 'Thu', 'value' => 71],
            ['label' => 'Fri', 'value' => 64],
            ['label' => 'Sat', 'value' => 88],
            ['label' => 'Sun', 'value' => 53],
        ];
        $maxSale = max(array_column($salesChart, 'value')) ?: 1;

        $lowStock = $lowStock ?? [];
    @endphp

    <!-- Header bar: title context lives in layout, this row carries controls -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8">
        <p class="text-sm text-gray-400">Here's what's happening with your store today.</p>
        {{-- <div class="flex items-center gap-2">
            <select
                class="text-xs font-medium text-gray-600 bg-white border border-[#19140010] rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>Last 90 days</option>
            </select>
            <button type="button"
                class="flex items-center gap-1.5 text-xs font-semibold text-white bg-[#1b1b18] hover:bg-black rounded-xl px-3.5 py-2 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                </svg>
                Export
            </button>
        </div> --}}
    </div>

    @if (!empty($lowStock))
        <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-3.5 mb-8">
            <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m0 3.75h.008M10.29 3.86l-8.18 14.18A1.5 1.5 0 003.5 20.5h17a1.5 1.5 0 001.39-2.46L13.71 3.86a1.5 1.5 0 00-2.42 0z" />
            </svg>
            <p class="text-xs font-medium text-amber-700">
                {{ count($lowStock) }} product{{ count($lowStock) > 1 ? 's are' : ' is' }} running low on stock.
            </p>
            <a href="{{ route('admin.products.index') }}"
                class="text-xs font-semibold text-amber-700 underline hover:text-amber-900 ml-auto">Review stock →</a>
        </div>
    @endif

    <!-- Stat cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <div class="bg-white border border-[#19140010] rounded-2xl p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.66 0-3 .9-3 2s1.34 2 3 2 3 .9 3 2-1.34 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2" />
                    </svg>
                </div>

            </div>
            <p class="text-xs uppercase tracking-wider text-gray-400 font-medium">Total Revenue</p>
            <p class="mt-1.5 text-2xl font-bold text-[#1b1b18]">${{ number_format($stats['total_revenue'], 2) }}</p>
        </div>

        <div class="bg-white border border-[#19140010] rounded-2xl p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 2h6l1 4H8l1-4zM5 6h14l1.4 13.2A2 2 0 0118.4 21H5.6a2 2 0 01-2-1.8L5 6z" />
                    </svg>
                </div>

            </div>
            <p class="text-xs uppercase tracking-wider text-gray-400 font-medium">Total Orders</p>
            <p class="mt-1.5 text-2xl font-bold text-[#1b1b18]">{{ $stats['total_orders'] }}</p>
        </div>

        <div class="bg-white border border-[#19140010] rounded-2xl p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                    </svg>
                </div>

            </div>
            <p class="text-xs uppercase tracking-wider text-gray-400 font-medium">Awaiting Processing</p>
            <p class="mt-1.5 text-2xl font-bold text-[#1b1b18]">{{ $stats['pending_orders'] }}</p>
        </div>

        <div class="bg-white border border-[#19140010] rounded-2xl p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-sky-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-sky-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>

            </div>
            <p class="text-xs uppercase tracking-wider text-gray-400 font-medium">Products</p>
            <p class="mt-1.5 text-2xl font-bold text-[#1b1b18]">{{ $stats['total_products'] }}</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-[#1b1b18]">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}"
                    class="text-xs font-semibold text-gray-500 hover:text-black transition-colors">
                    View all →
                </a>
            </div>

            @if (empty($recentOrders))
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
                    <p class="text-gray-400 text-xs max-w-xs mx-auto">Orders placed by customers will show up here.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($recentOrders as $order)
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                            class="block bg-white border border-[#19140010] rounded-2xl p-5 transition-all hover:shadow-md hover:border-indigo-100">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div
                                        class="w-9 h-9 rounded-full bg-[#1b1b18] text-white flex items-center justify-center text-[11px] font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($order->user_name ?? 'G', 0, 1)) }}
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2.5">
                                            <span
                                                class="text-xs font-semibold tracking-wider text-gray-400 uppercase">#{{ $order->id }}</span>
                                            <span class="text-xs text-gray-300">•</span>
                                            <span
                                                class="text-xs text-gray-600 font-medium">{{ $order->user->name ?? 'Guest' }}</span>
                                        </div>
                                        <p class="text-[11px] text-gray-400">
                                            {{ date('M d, Y · H:i', strtotime($order->created_at)) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-5">
                                    @include('admin.partials.status-badge', ['status' => $order->status])
                                    <div class="text-right">
                                        <span
                                            class="text-[10px] text-gray-400 block uppercase tracking-wider font-medium">Total</span>
                                        <span
                                            class="text-sm font-bold text-[#1b1b18]">${{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-[#1b1b18]">New Customers</h2>
                <a href="{{ route('admin.customers.index') }}"
                    class="text-xs font-semibold text-gray-500 hover:text-black transition-colors">
                    View all →
                </a>
            </div>
            <div class="bg-white border border-[#19140010] rounded-2xl divide-y divide-[#19140010] overflow-hidden">
                @forelse ($recentCustomers as $customer)
                    <div class="flex items-center gap-3 p-4">
                        <div
                            class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-[11px] font-semibold flex-shrink-0">
                            {{ strtoupper(substr($customer['name'], 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-[#1b1b18] truncate">{{ $customer['name'] }}</p>
                            <p class="text-[11px] text-gray-400 truncate">{{ $customer['email'] }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-semibold text-[#1b1b18]">{{ $customer['orders'] }} orders</p>
                            <p class="text-[11px] text-gray-400">{{ $customer['joined'] }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 p-4">No customers yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Sales overview + Top products -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">

        {{--  <div class="lg:col-span-2 bg-white border border-[#19140010] rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-sm font-semibold text-[#1b1b18]">Sales Overview</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Revenue collected per day</p>
                </div>
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-full px-2.5 py-1">This week</span>
            </div>
            <div class="flex items-end justify-between gap-2 h-40">
                @foreach ($salesChart as $day)
                    @php $heightPct = max(6, round(($day['value'] / $maxSale) * 100)); @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 group">
                        <div class="relative w-full flex justify-center" style="height: 9rem;">
                            <div class="w-full max-w-[28px] rounded-t-lg bg-gradient-to-t from-indigo-500 to-indigo-300 group-hover:from-indigo-600 group-hover:to-indigo-400 transition-colors self-end"
                                style="height: {{ $heightPct }}%;" title="${{ number_format($day['value'], 2) }}">
                            </div>
                        </div>
                        <span class="text-[11px] font-medium text-gray-400">{{ $day['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div> --}}

        <div class="bg-white border border-[#19140010] rounded-2xl p-6">
            <h2 class="text-sm font-semibold text-[#1b1b18] mb-5">Top Products</h2>
            <div class="space-y-4">
                @forelse ($topProducts as $product)
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gray-50 border border-[#19140010] flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if ($product['image'])
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                                    class="w-full h-full object-cover">
                            @else
                                <svg class="w-4.5 h-4.5 text-gray-300" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 7.5L12 3 3 7.5m18 0v9L12 21m9-13.5L12 11 3 7.5M12 11v10" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-[#1b1b18] truncate">{{ $product['name'] }}</p>
                            <p class="text-[11px] text-gray-400">{{ $product['sales'] }} sold</p>
                        </div>
                        <span
                            class="text-xs font-bold text-[#1b1b18] whitespace-nowrap">${{ number_format($product['revenue'], 2) }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No product sales yet.</p>
                @endforelse
            </div>
        </div>

    </div>



@endsection
