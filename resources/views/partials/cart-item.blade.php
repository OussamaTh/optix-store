<div class="flex gap-4 cart-item-row" data-cart-item-id="{{ $item->id }}">
    <div class="w-16 h-16 rounded-xl bg-gray-50 border border-[#19140010] overflow-hidden shrink-0">
        @if ($item->product?->image_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($item->product->image_path) }}"
                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
        @endif
    </div>

    <div class="flex-1 min-w-0">
        <div class="flex justify-between items-start gap-2">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-[#1b1b18] truncate">{{ $item->product->name }}</p>
                <p class="text-[11px] text-gray-400 uppercase tracking-wide mt-0.5">
                    {{ $item->variant->name ?? ($item->product->category->name ?? 'Item') }}
                </p>
            </div>
            <p class="text-sm font-bold text-[#1b1b18] shrink-0 cart-item-price">
                ${{ number_format($item->unit_price, 2) }}</p>
        </div>

        <div class="flex items-center justify-between mt-3">
            <div class="flex items-center gap-2 border border-[#19140010] rounded-full px-1">
                <form method="POST" action="{{ route('cart.decQt', $item->id) }}" class="cart-qty-form">
                    @csrf
                    <button type="button" data-action="decrement"
                        class="cart-qty-btn w-6 h-6 flex items-center justify-center text-gray-500 hover:text-[#1b1b18] transition-colors">−</button>
                </form>
                <span class="text-xs font-semibold w-4 text-center cart-qty-value">{{ $item->quantity }}</span>
                <form method="POST" action="{{ route('cart.incQt', $item->id) }}" class="cart-qty-form">
                    @csrf
                    <button type="button" data-action="increment"
                        class="cart-qty-btn w-6 h-6 flex items-center justify-center text-gray-500 hover:text-[#1b1b18] transition-colors">
                        +
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('cart.destroy', $item) }}" class="cart-delete-form">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="cart-delete-btn text-[10px] font-bold uppercase tracking-wider text-gray-400 hover:text-red-500 transition-colors">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
