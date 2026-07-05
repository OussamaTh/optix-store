<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Assumptions made about your schema (adjust to match your real columns):
 *  - Customers are rows in the `users` table with a `role` column equal to 'customer'.
 *    If you instead have a dedicated `Customer` model, swap `User::` for `Customer::`
 *    below and drop the `where('role', 'customer')` filter.
 *  - `users` has a hasMany `orders()` relationship (orders.user_id -> users.id).
 *  - `orders` has `total_amount` and `created_at` columns, matching the dashboard view.
 */
class CustomerController extends Controller
{
    /**
     * GET /admin/customers
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total_amount');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $customers = $users->getCollection()->map(function (User $user) {
            return [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'orders'      => $user->orders_count,
                'total_spent' => $user->orders_sum_total_amount ?? 0,
                'status'      => $user->orders_count > 0 ? 'active' : 'inactive',
                'joined'      => $user->created_at->format('M d, Y'),
            ];
        });

        $customerStats = [
            'total'           => User::where('role', 'customer')->count(),
            'new_this_month'  => User::where('role', 'customer')
                                    ->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count(),
            'returning_rate'  => $this->returningRate(),
        ];

        return view('admin.customers.index', [
            'customers'     => $customers,
            'customerStats' => $customerStats,
            'paginator'     => $users,
        ]);
    }

    /**
     * GET /admin/customers/{customer}
     */
    public function show(User $customer)
    {
        $customer->loadCount('orders')->loadSum('orders', 'total_amount');

        $orders = $customer->orders()->latest()->paginate(10);

        return view('admin.customers.show', [
            'customer' => $customer,
            'orders'   => $orders,
        ]);
    }

    /**
     * % of customers with more than one order.
     */
    private function returningRate(): int
    {
        $total = User::where('role', 'customer')->withCount('orders')->get();

        if ($total->isEmpty()) {
            return 0;
        }

        $returning = $total->filter(fn (User $u) => $u->orders_count > 1)->count();

        return (int) round(($returning / $total->count()) * 100);
    }
}