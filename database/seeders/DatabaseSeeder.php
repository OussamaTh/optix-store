<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Products
        $this->call(ProductSeeder::class);
        $products = Product::all();

        // 2. Seed Test User
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Clean up previous records if they exist to prevent duplication during re-seed
        $user->addresses()->delete();
        $user->orders()->delete();
        $user->wishlists()->delete();

        // 3. Seed Addresses
        $addressDefault = Address::create([
            'user_id' => $user->id,
            'recipient_name' => 'John Doe',
            'phone' => '+1 (555) 019-2834',
            'address_line1' => '123 Clarity Way',
            'address_line2' => 'Suite 400',
            'city' => 'San Francisco',
            'state' => 'CA',
            'postal_code' => '94103',
            'country' => 'United States',
            'is_default' => true,
        ]);

        $addressSecondary = Address::create([
            'user_id' => $user->id,
            'recipient_name' => 'John Doe',
            'phone' => '+1 (555) 019-2834',
            'address_line1' => '55 Light Avenue',
            'address_line2' => null,
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10010',
            'country' => 'United States',
            'is_default' => false,
        ]);

        // Helper to format addresses for order snapshots
        $formatAddress = function (Address $addr) {
            return $addr->recipient_name . "\n"
                . "Phone: " . $addr->phone . "\n"
                . $addr->address_line1 . "\n"
                . ($addr->address_line2 ? $addr->address_line2 . "\n" : "")
                . $addr->city . ", " . $addr->state . " " . $addr->postal_code . "\n"
                . $addr->country;
        };

        $defaultAddressText = $formatAddress($addressDefault);
        $secondaryAddressText = $formatAddress($addressSecondary);

        // 4. Seed Wishlist
        // Add Horizon (index 1) and Viper (index 3) to wishlist
        if ($products->count() >= 4) {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $products[1]->id, // Horizon
            ]);
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $products[3]->id, // Viper
            ]);
        }

        // 5. Seed Historical Orders
        // Order 1: Delivered
        $order1 = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-2026-1092',
            'total_amount' => 308.00,
            'status' => 'delivered',
            'tracking_code' => 'TRK87263541',
            'delivery_address' => $defaultAddressText,
            'created_at' => now()->subDays(14),
            'updated_at' => now()->subDays(10),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $products[0]->id, // Nova
            'product_name' => $products[0]->name,
            'product_image' => $products[0]->image_path,
            'quantity' => 1,
            'price' => $products[0]->price,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $products[2]->id, // Ember
            'product_name' => $products[2]->name,
            'product_image' => $products[2]->image_path,
            'quantity' => 1,
            'price' => $products[2]->price,
        ]);

        // Order 2: Shipped
        $order2 = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-2026-3849',
            'total_amount' => 169.00,
            'status' => 'shipped',
            'tracking_code' => 'TRK19283746',
            'delivery_address' => $defaultAddressText,
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(1),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $products[1]->id, // Horizon
            'product_name' => $products[1]->name,
            'product_image' => $products[1]->image_path,
            'quantity' => 1,
            'price' => $products[1]->price,
        ]);

        // Order 3: Processing
        $order3 = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-2026-7263',
            'total_amount' => 189.00,
            'status' => 'processing',
            'tracking_code' => null,
            'delivery_address' => $secondaryAddressText,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $products[3]->id, // Viper
            'product_name' => $products[3]->name,
            'product_image' => $products[3]->image_path,
            'quantity' => 1,
            'price' => $products[3]->price,
        ]);

        // Order 4: Cancelled
        $order4 = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-2026-0182',
            'total_amount' => 149.00,
            'status' => 'cancelled',
            'tracking_code' => null,
            'delivery_address' => $defaultAddressText,
            'created_at' => now()->subDays(30),
            'updated_at' => now()->subDays(29),
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'product_id' => $products[0]->id, // Nova
            'product_name' => $products[0]->name,
            'product_image' => $products[0]->image_path,
            'quantity' => 1,
            'price' => $products[0]->price,
        ]);
    }
}
