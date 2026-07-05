<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Add a product to the authenticated user's cart.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'variant_id' => 'nullable|integer|exists:variants,id',
        ]);

        $user = auth()->user();
        $product = Product::findOrFail($validated['product_id']);

        // If the product has variants, a variant selection is mandatory.
        if ($product->variants()->exists() && empty($validated['variant_id'])) {
            return redirect()->back()->withErrors(['variant_id' => 'Please select a variant.']);
        }

        // Make sure the submitted variant actually belongs to this product.
        if (! empty($validated['variant_id']) && ! $product->variants()->where('variants.id', $validated['variant_id'])->exists()) {
            return redirect()->back()->withErrors(['variant_id' => 'Invalid variant for this product.']);
        }

        $cartItem = $user->addToCart($product, $validated['quantity'], $validated['variant_id'] ?? null);

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $cartItems = $user->cartItems()->with(['product', 'variant'])->get();
            $subtotal = $cartItems->sum->subtotal;

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cartCount' => $cartItems->sum('quantity'),
                'cartItemHtml' => view('partials.cart-item', ['item' => $cartItem->load(['product', 'variant'])])->render(),
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart.');
    }

    public function productsWithGlassPro(){
        $foundProducts = Product::where("");
    }

    /**
     * Render the cart sidebar partial (used for full AJAX refresh, if needed).
     */
    public function sidebar()
    {
        $cartItems = auth()->user()->cartItems()->with(['product', 'variant'])->get();

        return view('partials.cartSidebar', compact('cartItems'));
    }

    /**
     * Set an exact quantity for a cart item.
     */
    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        $this->authorizeOwnership($cartItem);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update(['quantity' => $validated['quantity']]);

        return $this->cartItemResponse($cartItem);
    }

    /**
     * Increment a cart item's quantity by 1.
     */
    public function incQt(CartItem $cartItem): JsonResponse
    {
        $this->authorizeOwnership($cartItem);

        $cartItem->increment('quantity');

        return $this->cartItemResponse($cartItem);
    }

    /**
     * Decrement a cart item's quantity by 1, removing it once it hits 0.
     */
    public function decQt(CartItem $cartItem): JsonResponse
    {
        $this->authorizeOwnership($cartItem);

        if ($cartItem->quantity <= 1) {
            $cartItem->delete();

            return response()->json([
                'removed' => true,
                'cart_item_id' => $cartItem->id,
                ...$this->cartTotals(),
            ]);
        }

        $cartItem->decrement('quantity');

        return $this->cartItemResponse($cartItem);
    }

    /**
     * Remove a cart item entirely.
     */
    public function destroy(CartItem $cartItem): JsonResponse
    {
        $this->authorizeOwnership($cartItem);

        $cartItem->delete();

        $user = auth()->user();
        $cartItems = $user->cartItems()->with(['product', 'variant'])->get();
        $subtotal = $cartItems->sum->subtotal;

        return response()->json([
            'removed' => true,
            'cart_item_id' => $cartItem->id,
            'cartCount' => $cartItems->sum('quantity'),
            'subtotal' => $subtotal,
            'total' => $subtotal, // add delivery if applicable
        ]);
    }

    /**
     * Make sure the cart item actually belongs to the logged-in user.
     */
    private function authorizeOwnership(CartItem $cartItem): void
    {
        abort_unless($cartItem->user_id === auth()->id(), 403);
    }

    private function cartItemResponse(CartItem $cartItem): JsonResponse
    {
        return response()->json([
            'removed' => false,
            'cart_item_id' => $cartItem->id,
            'quantity' => $cartItem->quantity,
            'unit_price' => number_format($cartItem->unit_price, 2),
            'item_subtotal' => number_format($cartItem->subtotal, 2),
            ...$this->cartTotals(),
        ]);
    }

    private function cartTotals(): array
    {
        $cartItems = auth()->user()->cartItems()->with(['product', 'variant'])->get();
        $subtotal = $cartItems->sum->subtotal;
        $delivery = 0;
        $total = $subtotal + $delivery;

        return [
            'subtotal' => number_format($subtotal, 2),
            'delivery' => $delivery > 0 ? number_format($delivery, 2) : null,
            'total' => number_format($total, 2),
            'count' => $cartItems->sum('quantity'),
        ];
    }
}