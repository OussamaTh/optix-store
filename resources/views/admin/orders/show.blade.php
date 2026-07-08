@extends('appAdminLayout.layout')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order #' . $order->order_number)

@section('content')

    @php
        // processing -> shipped -> delivered. cancelled is a separate
        // terminal branch, not a step, so it's excluded from the stepper.
$statusFlow = ['processing', 'shipped', 'delivered'];
$currentIndex = array_search($order->status, $statusFlow, true);
$isCancelled = $order->status === 'cancelled';
        $nextStatus =
            $currentIndex !== false && $currentIndex < count($statusFlow) - 1 ? $statusFlow[$currentIndex + 1] : null;
        $itemsTotal = $order->items->sum(fn($item) => $item->quantity * $item->price ?? 0);
    @endphp

    <a href="{{ route('admin.orders.index') }}"
        class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-black transition-colors mb-6">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Orders
    </a>

    @if (session('success'))
        <div
            class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium rounded-2xl px-4 py-3 mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div
            class="flex items-center gap-2 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium rounded-2xl px-4 py-3 mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2 space-y-5">

            <div class="bg-white border border-[#19140010] rounded-2xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-semibold text-[#1b1b18]">Order #{{ $order->order_number }}</h2>
                    <span class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y · h:i A') }}</span>
                </div>

                <div class="divide-y divide-[#19140010]">
                    @foreach ($order->items as $item)
                        <div class="flex items-center gap-4 py-4 first:pt-0">
                            <div
                                class="w-12 h-12 rounded-xl bg-gray-50 border border-[#19140010] flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if ($item->product && $item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 7.5L12 3 3 7.5m18 0v9L12 21m9-13.5L12 11 3 7.5M12 11v10" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-[#1b1b18] truncate">
                                    {{ $item->product->name ?? 'Product' }}</p>
                                <p class="text-xs text-gray-400">Qty {{ $item->quantity }} ·
                                    ${{ number_format($item->price, 2) }} each</p>
                            </div>
                            <span
                                class="text-sm font-bold text-[#1b1b18] whitespace-nowrap">{{ number_format($item->quantity * $item->price, 2) }} DH</span>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between pt-4 mt-2 border-t border-[#19140010]">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Items Total</span>
                    <span class="text-base font-bold text-[#1b1b18]">{{ number_format($order->total_amount, 2) }} DH</span>
                </div>
            </div>

            <div class="bg-white border border-[#19140010] rounded-2xl p-6">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Delivery Address</h2>
                <p class="text-sm text-gray-600">
                    {{ $order->shipping_address ?? 'No delivery address on file.' }}
                </p>
            </div>

        </div>

        <div class="space-y-5">

            <div class="bg-white border border-[#19140010] rounded-2xl p-6">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-4">Customer</h2>
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-full bg-[#1b1b18] text-white flex items-center justify-center text-xs font-semibold flex-shrink-0">
                        {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-[#1b1b18] truncate">{{ $order->user->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $order->user->email ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-[#19140010] rounded-2xl p-6">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-5">Order Status</h2>

                @if ($isCancelled)
                    <div class="mb-5">
                        @include('admin.partials.status-badge', ['status' => 'cancelled'])
                    </div>
                    <p class="text-xs text-gray-400">This order was cancelled and can't be advanced further.</p>
                @else
                    <!-- Stepper -->
                    <div class="flex items-center mb-6">
                        @foreach ($statusFlow as $index => $step)
                            @php
                                // A step is "done" if it's before the current one,
// OR it IS the current one but there's no next step
                                // left (i.e. delivered is the final, completed stage).
                                $isDone = $index < $currentIndex || ($index === $currentIndex && !$nextStatus);
                                $isCurrent = $index === $currentIndex && $nextStatus;
                            @endphp
                            <div class="flex items-center flex-1 last:flex-none">
                                <div class="flex flex-col items-center gap-1.5">
                                    <div
                                        class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold border-2
                                        {{ $isDone ? 'bg-emerald-500 border-emerald-500 text-white' : ($isCurrent ? 'bg-[#1b1b18] border-[#1b1b18] text-white' : 'bg-white border-gray-200 text-gray-300') }}">
                                        @if ($isDone)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <span
                                        class="text-[10px] font-semibold uppercase tracking-wider {{ $isCurrent || $isDone ? 'text-[#1b1b18]' : 'text-gray-400' }}">
                                        {{ $step }}
                                    </span>
                                </div>
                                @if (!$loop->last)
                                    <div class="flex-1 h-0.5 mx-1 mb-4 {{ $isDone ? 'bg-emerald-500' : 'bg-gray-200' }}">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2.5">
                        @if ($nextStatus)
                            <form method="POST" action="{{ route('admin.orders.advanceStatus', $order) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#1b1b18] hover:bg-black rounded-xl text-xs font-semibold text-white transition-colors">
                                    Mark as {{ ucfirst($nextStatus) }}
                                </button>
                            </form>
                        @else
                            <div
                                class="w-full text-center px-4 py-2.5 bg-emerald-50 rounded-xl text-xs font-semibold text-emerald-700">
                                Order delivered
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                            onsubmit="return confirm('Cancel order #{{ $order->order_number }}? This can\'t be undone.');">
                            @csrf
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-rose-200 hover:bg-rose-50 rounded-xl text-xs font-semibold text-rose-600 transition-colors">
                                Cancel Order
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="bg-white border border-[#19140010] rounded-2xl p-6">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-4">Tracking Code</h2>
                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $order->status }}">
                    <input type="text" name="tracking_code" value="{{ old('tracking_code', $order->tracking_code) }}"
                        placeholder="e.g. 1Z999AA10123456784"
                        class="w-full px-3.5 py-2.5 text-sm bg-white border border-[#19140010] rounded-xl focus:outline-none focus:border-[#1b1b18]/30 transition-colors">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl text-xs font-semibold text-[#1b1b18] transition-colors">
                        Save Tracking Code
                    </button>
                </form>
            </div>

        </div>

    </div>

@endsection
