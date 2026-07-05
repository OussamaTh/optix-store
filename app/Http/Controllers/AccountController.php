<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display the authenticated user's dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Eager load order items and their associated products
        $orders = $user->orders()->with('items.product')->get();

        $addresses = $user->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();

        $wishlist = $user->wishlistProducts()->get();

        // Get the active tab from the query parameters, defaulting to 'orders'
        $activeTab = $request->query('tab', 'orders');

        // List of valid tabs to prevent injections
        $validTabs = ['orders', 'addresses', 'profile', 'wishlist'];
        if (!in_array($activeTab, $validTabs)) {
            $activeTab = 'orders';
        }

        return view('account.index', compact('user', 'orders', 'addresses', 'wishlist', 'activeTab'));
    }

    /**
     * Simulate placing an order.
     */
    public function simulateOrder(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'address_id' => ['required', 'exists:addresses,id'],
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);
        $address = Address::where('id', $request->address_id)->where('user_id', $user->id)->firstOrFail();

        // Format delivery address snapshot
        $addressText = $address->recipient_name . "\n"
            . 'Phone: ' . $address->phone . "\n"
            . $address->address_line1 . "\n"
            . ($address->address_line2 ? $address->address_line2 . "\n" : '')
            . $address->city . ', ' . $address->state . ' ' . $address->postal_code . "\n"
            . $address->country;

        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);

        // Random status for variety in simulation: mostly processing, but could be shipped/delivered
        $statuses = ['processing', 'processing', 'shipped', 'delivered'];
        $status = $statuses[array_rand($statuses)];
        $trackingCode = ($status === 'shipped' || $status === 'delivered') ? 'TRK' . rand(10000000, 99999999) : null;

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => $orderNumber,
            'total_amount' => $product->price,
            'status' => $status,
            'tracking_code' => $trackingCode,
            'delivery_address' => $addressText,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_image' => $product->image_path,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        // Remove from wishlist if it is there
        Wishlist::where('user_id', $user->id)->where('product_id', $product->id)->delete();

        session()->flash('success', "Order #{$orderNumber} placed successfully (simulated)!");

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'order_number' => $orderNumber,
        ]);
    }


    public function tracking(Order $order)
    {
        // Ensure the order belongs to the logged-in user
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items');

        return view('account.order-tracking', compact('order'));
    }
}
