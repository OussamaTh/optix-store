@extends('appAdminLayout.layout')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')

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

    <!-- Status filter tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6">
        @php
            $tabs = [
                '' => 'All',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ];

            // processing -> shipped -> delivered. Used to compute each
            // row's "next status" quick action below.
$statusFlow = ['processing', 'shipped', 'delivered'];
        @endphp
        @foreach ($tabs as $value => $label)
            <a href="{{ route('admin.orders.index', array_filter(['status' => $value, 'q' => request('q')])) }}"
                class="shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold border transition-colors {{ request('status', '') === $value ? 'bg-black text-white border-black' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                {{ $label }}
                <span class="text-[10px] {{ request('status', '') === $value ? 'text-white/70' : 'text-gray-400' }}">
                    {{ $statusCounts[$value === '' ? 'all' : $value] }}
                </span>
            </a>
        @endforeach
    </div>

    <form method="GET" action="{{ route('admin.orders.index') }}" class="max-w-sm mb-8">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <div class="relative">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search order # or customer..."
                class="w-full pl-10 pr-4 py-2.5 text-sm bg-white border border-[#19140010] rounded-full focus:outline-none focus:border-[#1b1b18]/30 transition-colors">
        </div>
    </form>

    @if ($orders->isEmpty())
        <div class="border border-dashed border-gray-200 rounded-3xl p-16 text-center">
            <div
                class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-[#1b1b18] mb-1">No orders found</h3>
            <p class="text-gray-400 text-xs max-w-xs mx-auto">Try a different filter or search term.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
                @php
                    $currentIndex = array_search($order->status, $statusFlow, true);
                    $nextStatus =
                        $currentIndex !== false && $currentIndex < count($statusFlow) - 1
                            ? $statusFlow[$currentIndex + 1]
                            : null;

                    // Button color reflects the status it's moving the order INTO.
$nextStatusButtonStyles = [
    'processing' => 'bg-amber-500 hover:bg-amber-600',
    'shipped' => 'bg-sky-500 hover:bg-sky-600',
    'delivered' => 'bg-emerald-500 hover:bg-emerald-600',
];
$nextStatusButtonClass = $nextStatusButtonStyles[$nextStatus] ?? 'bg-[#1b1b18] hover:bg-black';
                @endphp
                <div class="bg-white border border-[#19140010] rounded-2xl p-6 transition-all hover:shadow-md">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5">

                        <div class="flex items-start gap-3.5 flex-1 min-w-0">
                            <input type="checkbox" value="{{ $order->id }}"
                                class="order-select-checkbox mt-1 w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-400 cursor-pointer flex-shrink-0">

                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="flex items-start gap-3.5 flex-1 min-w-0">
                                <div
                                    class="w-10 h-10 rounded-full bg-[#1b1b18] text-white flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                                </div>
                                <div class="min-w-0 space-y-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span
                                            class="text-sm font-semibold text-[#1b1b18] truncate">{{ $order->user->name ?? 'Guest' }}</span>
                                        @include('admin.partials.status-badge', [
                                            'status' => $order->status,
                                        ])
                                    </div>
                                    <div class="flex items-center gap-2 flex-wrap text-xs text-gray-400">
                                        <span
                                            class="font-semibold tracking-wider uppercase text-gray-500">#{{ $order->order_number }}</span>
                                        @if ($order->user->email ?? null)
                                            <span>•</span>
                                            <span class="truncate">{{ $order->user->email }}</span>
                                        @endif
                                        <span>•</span>
                                        <span>{{ $order->created_at->format('M d, Y · h:i A') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 flex-wrap text-[11px] text-gray-400">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            {{ $order->items_count ?? 0 }}
                                            item{{ ($order->items_count ?? 0) === 1 ? '' : 's' }}
                                        </span>
                                        @if (!empty($order->tracking_code))
                                            <span class="inline-flex items-center gap-1 font-semibold text-indigo-600">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 17v-2a4 4 0 014-4h6M9 17l-4-4 4-4m6-3l4 4-4 4" />
                                                </svg>
                                                Tracking: {{ $order->tracking_code }}
                                            </span>
                                        @elseif (in_array($order->status, ['shipped', 'delivered'], true))
                                            <span class="inline-flex items-center gap-1 text-amber-600 font-medium">
                                                No tracking code added
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto justify-between lg:justify-end">
                            <div class="text-right">
                                <span class="text-xs text-gray-400 block uppercase tracking-wider font-medium">Total</span>
                                <span
                                    class="text-base font-bold text-[#1b1b18]">${{ number_format($order->total_amount, 2) }}</span>
                            </div>

                            @if ($nextStatus)
                                <form method="POST" action="{{ route('admin.orders.advanceStatus', $order) }}">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-4 py-2 {{ $nextStatusButtonClass }} rounded-full text-xs font-semibold text-white transition-colors whitespace-nowrap">
                                        Mark as {{ ucfirst($nextStatus) }}
                                    </button>
                                </form>
                            @endif

                            {{-- @if ($order->status === 'cancelled')
                                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                                    onsubmit="return confirm('Permanently delete order #{{ $order->order_number }}? This can\'t be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-rose-200 hover:bg-rose-50 rounded-full text-xs font-semibold text-rose-600 transition-colors whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M4 7h16M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            @endif --}}

                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="inline-flex items-center justify-center px-5 py-2 border border-gray-200 rounded-full text-xs font-semibold hover:bg-gray-50 text-black transition-colors whitespace-nowrap">
                                Manage
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif

    <!-- Hidden form the floating bar submits into. Kept separate from the
                 row markup above so per-row forms (advance status, delete) never
                 end up nested inside another form. -->
    {{--  <form id="bulk-delete-form" method="POST" action="{{ route('admin.orders.bulkDestroy') }}" class="hidden">
        @csrf
        @method('DELETE')
    </form> --}}

    <!-- Floating selection island: hidden until more than one order is checked -->
    <div id="bulk-actions-bar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50">
        <div class="flex items-center gap-1 bg-[#1b1b18] text-white rounded-full pl-5 pr-2 py-2 shadow-2xl">
            <span id="bulk-selected-count" class="text-xs font-semibold whitespace-nowrap mr-2">0 selected</span>
            <button type="button" id="bulk-delete-btn"
                class="inline-flex items-center gap-1.5 bg-white text-[#1b1b18] hover:bg-gray-100 rounded-full px-3.5 py-1.5 text-xs font-semibold transition-colors whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M4 7h16M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                </svg>
                Delete
            </button>
            <button type="button" id="bulk-clear-btn"
                class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors flex-shrink-0"
                aria-label="Clear selection">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.order-select-checkbox');
            const bar = document.getElementById('bulk-actions-bar');
            const countLabel = document.getElementById('bulk-selected-count');
            const deleteBtn = document.getElementById('bulk-delete-btn');
            const clearBtn = document.getElementById('bulk-clear-btn');
            const bulkForm = document.getElementById('bulk-delete-form');

            function getChecked() {
                return Array.from(checkboxes).filter(cb => cb.checked);
            }

            function refreshBar() {
                const checked = getChecked();
                // Island only appears once more than one order is selected —
                // a single cancelled order can already be deleted inline.
                if (checked.length > 1) {
                    bar.classList.remove('hidden');
                    countLabel.textContent = checked.length + ' selected';
                } else {
                    bar.classList.add('hidden');
                }
            }

            checkboxes.forEach(cb => cb.addEventListener('change', refreshBar));

            clearBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => (cb.checked = false));
                refreshBar();
            });

            deleteBtn.addEventListener('click', function() {
                const checked = getChecked();
                if (checked.length === 0) return;

                const confirmed = confirm(
                    `Delete ${checked.length} selected order(s)? Only cancelled orders will actually be removed — anything else will be skipped.`
                );
                if (!confirmed) return;

                bulkForm.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = cb.value;
                    bulkForm.appendChild(input);
                });
                bulkForm.submit();
            });
        });
    </script>

@endsection
