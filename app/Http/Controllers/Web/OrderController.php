<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;

class OrderController extends Controller
{
    /**
     * Display the authenticated customer's orders.
     */
    public function index(): View
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view(
            'orders.index',
            compact('orders')
        );
    }

    /**
     * Display a single order belonging to the authenticated customer.
     */
    public function show(string $orderNumber): View
    {
        $order = Order::query()
            ->where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with([
                'items.product',
                'items.dealer',
                'address',
                'payment',
                'statusHistories',
            ])
            ->firstOrFail();

        return view(
            'orders.show',
            compact('order')
        );
    }
}