@php
    $styles = [
        'pending'    => 'bg-amber-50 text-amber-700',
        'processing' => 'bg-sky-50 text-sky-700',
        'shipped'    => 'bg-indigo-50 text-indigo-700',
        'completed'  => 'bg-emerald-50 text-emerald-700',
        'delivered'  => 'bg-emerald-50 text-emerald-700',
        'cancelled'  => 'bg-rose-50 text-rose-700',
        'refunded'   => 'bg-gray-100 text-gray-500',
        'active'     => 'bg-emerald-50 text-emerald-700',
        'inactive'   => 'bg-gray-100 text-gray-500',
    ];
    $key = strtolower($status ?? 'pending');
    $style = $styles[$key] ?? 'bg-gray-100 text-gray-500';
@endphp

<span class="inline-flex items-center text-[11px] font-semibold capitalize {{ $style }} rounded-full px-2.5 py-1">
    {{ $key }}
</span>