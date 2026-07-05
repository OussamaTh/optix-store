<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout.index');
    }

    public function store(Request $request)
    {


        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
            'payment_method' => 'required|in:cod,card',
            'address_id' => 'required',
            'full_name' => 'nullable|required_if:address_id,new|max:255',
            'street_address' => 'nullable|required_if:address_id,new|max:500',
            'city' => 'nullable|required_if:address_id,new|max:100',
            'postal_code' => 'nullable|required_if:address_id,new|max:20',
            'country' => 'nullable|required_if:address_id,new|max:100',
        ]);

        $user = auth()->user();
        $cartItems = $user->cartItems()->with(['product', 'variant'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return DB::transaction(function () use ($request, $user, $cartItems) {
            // Handle address
            if ($request->address_id === 'new') {
                $address = $user->addresses()->create([
                    'full_name' => $request->full_name,
                    'phone' => $request->phone,
                    'street_address' => $request->street_address,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'country' => $request->country,
                    'is_default' => $user->addresses()->count() === 0,
                ]);
                $addressId = $address->id;
            } else {
                $addressId = $request->address_id;
            }

            $subtotal = $cartItems->sum->subtotal;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $addressId,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'total_amount' => $subtotal,
                'status' => 'processing',
                'tracking_code' => null,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_image' => $item->product->image_path,
                    'variant_id' => $item->variant_id,
                    'variant_name' => $item->variant->name ?? null,
                    'quantity' => $item->quantity,
                    'price' => $item->unit_price,
                ]);
            }

            // Clear cart
            $user->cartItems()->delete();

            return redirect()->route('main-page')->with('success', 'Order placed successfully!');
        });
    }
}
