<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'processing')->count(),
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
        ];

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        /* dd($recentOrders); */

        $topProducts = $this->getTopProducts();

        $recentCustomers = $this->getRecentCustomers();

        $lowStockNotice = null; // no stock column on products yet — placeholder for future use

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'recentCustomers', 'lowStockNotice'));
    }

    /**
     * Best-selling products, ranked by units sold (excludes cancelled orders).
     */
    private function getTopProducts(int $limit = 5): array
    {
        return Product::query()
            ->select('products.id', 'products.name', 'products.image_path')
            ->selectRaw('SUM(order_items.quantity) as total_sales')
            ->selectRaw('SUM(order_items.quantity * order_items.price) as total_revenue')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name', 'products.image_path')
            ->orderByDesc('total_sales')
            ->take($limit)
            ->get()
            ->map(fn($product) => [
                'name'    => $product->name,
                'sales'   => (int) $product->total_sales,
                'revenue' => (float) $product->total_revenue,
                'image'   => $product->image_path
                    ? \Illuminate\Support\Facades\Storage::url($product->image_path)
                    : null,
            ])
            ->toArray();
    }

    /**
     * Most recently joined customers, with their lifetime order count.
     * Cancelled orders are still counted here since this is "how many
     * orders has this person ever placed", not a revenue figure.
     */
    private function getRecentCustomers(int $limit = 5): array
    {
        return User::query()
            ->where('role', 'customer')
            ->withCount('orders')
            ->orderByDesc('created_at')
            ->take($limit)
            ->get()
            ->map(fn($user) => [
                'name'   => $user->name,
                'email'  => $user->email,
                'orders' => $user->orders_count,
                'joined' => $user->created_at->diffForHumans(),
            ])
            ->toArray();
    }
}
