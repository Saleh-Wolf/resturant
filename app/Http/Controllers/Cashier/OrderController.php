<?php

namespace App\Http\Controllers\Cashier;

use App\Models\Order;
use App\Models\RestaurantTable;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'table',
            'waiter',
            'items.menuItem'
        ])
            ->where('status', 'ready')
            ->latest()
            ->get();

        $todaySales = Order::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total');

        $completedOrdersToday = Order::where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();

        return view(
            'cashier.orders.index',
            compact(
                'orders',
                'todaySales',
                'completedOrdersToday'
            )
        );
    }


    public function history()
    {
        $orders = Order::with([
            'table',
            'waiter'
        ])
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);

        return view(
            'cashier.orders.history',
            compact('orders')
        );
    }


    public function complete(Order $order)
    {
        $order->update([
            'status' => 'completed',
        ]);

        RestaurantTable::where(
            'id',
            $order->restaurant_table_id
        )->update([
            'status' => 'available',
        ]);

        return back()
            ->with(
                'success',
                'Order completed successfully'
            );
    }
}
