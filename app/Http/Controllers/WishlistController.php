<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Toggle a product in the user's wishlist (add if not present, remove if present).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id']
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $inWishlist = false;
            $message = 'Removed from wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            $inWishlist = true;
            $message = 'Added to wishlist.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'in_wishlist' => $inWishlist,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified wishlist item from storage.
     */
    public function destroy(Wishlist $wishlist)
    {
        // Ensure user owns this wishlist item
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        $wishlist->delete();

        return redirect()->route('account.index', ['tab' => 'wishlist'])
            ->with('success', 'Product removed from wishlist.');
    }
}
