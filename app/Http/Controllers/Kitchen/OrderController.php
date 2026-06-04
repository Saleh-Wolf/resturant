<?php

namespace App\Http\Controllers\Kitchen;

use App\Models\Order;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'table',
            'items.menuItem'
        ])
        ->whereIn('status', [
            'pending',
            'preparing',
            'ready'
        ])
        ->latest()
        ->get();

        return view(
            'kitchen.orders.index',
            compact('orders')
        );
    }

    public function startPreparing(Order $order)
    {
        $order->update([
            'status' => 'preparing',
        ]);

        return back()
            ->with('success', 'Order is now preparing');
    }

    public function markReady(Order $order)
    {
        $order->update([
            'status' => 'ready',
        ]);

        return back()
            ->with('success', 'Order is ready');
    }
}