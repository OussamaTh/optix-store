@extends('appAdminLayout.layout')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')

    @php
        
        $customerStats = $customerStats ?? [
            'total' => count($customers),
            'new_this_month' => 2,
            'returning_rate' => 38,
        ];
    @endphp

    <!-- Quick stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="bg-white border border-[#19140010] rounded-2xl p-6">
            <p class="text-xs uppercase tracking-wider text-gray-400 font-medium">Total Customers</p>
            <p class="mt-2 text-2xl font-bold text-[#1b1b18]">{{ $customerStats['total'] }}</p>
        </div>
        <div class="bg-white border border-[#19140010] rounded-2xl p-6">
            <p class="text-xs uppercase tracking-wider text-gray-400 font-medium">New This Month</p>
            <p class="mt-2 text-2xl font-bold text-[#1b1b18]">{{ $customerStats['new_this_month'] }}</p>
        </div>
        <div class="bg-white border border-[#19140010] rounded-2xl p-6">
            <p class="text-xs uppercase tracking-wider text-gray-400 font-medium">Returning Rate</p>
            <p class="mt-2 text-2xl font-bold text-[#1b1b18]">{{ $customerStats['returning_rate'] }}%</p>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
        <h2 class="text-xl font-semibold text-[#1b1b18]">All Customers</h2>
        <div class="flex items-center gap-2">
            <div class="relative">
                <svg class="w-3.5 h-3.5 text-gray-300 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35M19 11a8 8 0 11-16 0 8 8 0 0116 0z" />
                </svg>
                <input type="text" placeholder="Search customers..."
                    class="text-xs bg-white border border-[#19140010] rounded-xl pl-8 pr-3 py-2 w-56 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
            </div>
        </div>
    </div>

    @if (empty($customers))
        <div class="border border-dashed border-gray-200 rounded-3xl p-16 text-center">
            <h3 class="text-base font-semibold text-[#1b1b18] mb-1">No customers yet</h3>
            <p class="text-gray-400 text-xs max-w-xs mx-auto">Customers who sign up or check out will show up here.</p>
        </div>
    @else
        <div class="bg-white border border-[#19140010] rounded-2xl overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#19140010]">
                        <th class="text-[11px] uppercase tracking-wider text-gray-400 font-medium px-6 py-4">Customer</th>
                        <th class="text-[11px] uppercase tracking-wider text-gray-400 font-medium px-6 py-4">Orders</th>
                        <th class="text-[11px] uppercase tracking-wider text-gray-400 font-medium px-6 py-4">Total Spent
                        </th>
                        
                        <th class="text-[11px] uppercase tracking-wider text-gray-400 font-medium px-6 py-4">Joined</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#19140010]">
                    @foreach ($customers as $customer)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-[11px] font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($customer['name'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-[#1b1b18]">{{ $customer['name'] }}</p>
                                        <p class="text-[11px] text-gray-400">{{ $customer['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">{{ $customer['orders'] }}</td>
                            <td class="px-6 py-4 text-xs font-semibold text-[#1b1b18]">
                                {{ number_format($customer['total_spent'], 2) }} DH</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.customers.show', $customer['id']) }}"
                                    class="text-xs font-semibold text-gray-500 hover:text-black">View →</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
