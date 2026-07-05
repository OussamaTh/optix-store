<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * The logical, ordered lifecycle of an order. "cancelled" is intentionally
     * excluded — it's a terminal side-branch, not a step in this sequence.
     */
    private const STATUS_FLOW = ['processing', 'shipped', 'delivered'];

    public function index(Request $request): View
    {
        $orders = Order::with('user')
            ->withCount('items')
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->input('q');
                $query->where(function ($q) use ($term) {
                    $q
                        ->where('order_number', 'like', "%{$term}%")
                        ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $statusCounts = [
            'all' => Order::count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(string $orderId): View
    {
        $order = Order::with(['user', 'items.product'])->find($orderId);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:processing,shipped,delivered,cancelled'],
            'tracking_code' => ['nullable', 'string', 'max:255'],
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully.');
    }

    /**
     * Advance the order to the next status in the logical flow.
     * processing -> shipped -> delivered. Does nothing (safely) if the
     * order is cancelled or already at the final step.
     */
    public function advanceStatus(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        $currentIndex = array_search($order->status, self::STATUS_FLOW, true);

        if ($currentIndex === false) {
            $message = "Order is {$order->status} and can't be advanced through the standard flow.";

            return $request->wantsJson()
                ? response()->json(['message' => $message], 422)
                : redirect()->back()->with('error', $message);
        }

        if ($currentIndex === array_key_last(self::STATUS_FLOW)) {
            $message = 'Order is already delivered.';

            return $request->wantsJson()
                ? response()->json(['message' => $message], 422)
                : redirect()->back()->with('error', $message);
        }

        $nextStatus = self::STATUS_FLOW[$currentIndex + 1];
        $order->update(['status' => $nextStatus]);
        $followingStatus = self::STATUS_FLOW[$currentIndex + 2] ?? null;

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Order marked as {$nextStatus}.",
                'order_id' => $order->id,
                'status' => $nextStatus,
                'next_status' => $followingStatus,
            ]);
        }

        return redirect()->back()->with('success', "Order marked as {$nextStatus}.");
    }

    /**
     * Cancel an order. Blocked once an order has already been delivered.
     */
    public function cancel(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        if ($order->status === 'delivered') {
            $message = 'Delivered orders cannot be cancelled.';

            return $request->wantsJson()
                ? response()->json(['message' => $message], 422)
                : redirect()->back()->with('error', $message);
        }

        if ($order->status === 'cancelled') {
            $message = 'Order is already cancelled.';

            return $request->wantsJson()
                ? response()->json(['message' => $message], 422)
                : redirect()->back()->with('error', $message);
        }

        $order->update(['status' => 'cancelled']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Order cancelled.',
                'order_id' => $order->id,
                'status' => 'cancelled',
                'next_status' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Order cancelled.');
    }

    /**
     * Permanently delete a single order. Only cancelled orders are eligible
     * to be deleted, so an active/fulfilled order can never be removed
     * by accident from this action.
     */
    public function destroy(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        if ($order->status !== 'cancelled') {
            $message = 'Only cancelled orders can be deleted.';

            return $request->wantsJson()
                ? response()->json(['message' => $message], 422)
                : redirect()->back()->with('error', $message);
        }

        $orderId = $order->id;
        $order->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Order deleted.',
                'deleted_ids' => [$orderId],
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }

    /**
     * Delete several orders at once. Same rule as destroy(): only orders
     * that are cancelled are actually removed. Anything else in the
     * selection is silently skipped and reported back to the admin.
     */
    public function bulkDestroy(Request $request): RedirectResponse|JsonResponse
    {
        $ids = array_filter((array) $request->input('ids', []));

        if (empty($ids)) {
            $message = 'No orders were selected.';

            return $request->wantsJson()
                ? response()->json(['message' => $message], 422)
                : redirect()->back()->with('error', $message);
        }

        $deletableIds = Order::whereIn('id', $ids)->where('status', 'cancelled')->pluck('id');
        $skippedIds = array_values(array_diff($ids, $deletableIds->all()));

        Order::whereIn('id', $deletableIds)->delete();

        $message = $deletableIds->count() . ' order(s) deleted.';
        if (count($skippedIds) > 0) {
            $message .= ' ' . count($skippedIds) . ' order(s) were skipped because only cancelled orders can be deleted.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'deleted_ids' => $deletableIds->all(),
                'skipped_ids' => $skippedIds,
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', $message);
    }
}
