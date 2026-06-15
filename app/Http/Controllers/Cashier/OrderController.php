<?php

namespace App\Http\Controllers\Cashier;

use App\Models\Bill;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'table',
            'waiter',
            'items.menuItem',
            'items.offer',
        ])
            ->where('status', 'ready')
            ->whereDoesntHave('bill')
            ->latest()
            ->get();

        $todaySales = Bill::where('payment_status', 'paid')
            ->whereDate('paid_at', today())
            ->sum('grand_total');

        $completedOrdersToday = Bill::where('payment_status', 'paid')
            ->whereDate('paid_at', today())
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
            'waiter',
            'bill'
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
        if ($order->bill) {
            return back()
                ->with('error', 'This order already has a bill.');
        }

        $order->load([
            'items',
            'table',
            'waiter',
        ]);

        $subtotal = $order->items->sum(function ($item) {
            return $item->original_unit_price * $item->quantity;
        });

        $discountTotal = $order->items->sum(function ($item) {
            return $item->discount_amount * $item->quantity;
        });

        $taxAmount = 0;
        $serviceCharge = 0;
        $grandTotal = $order->total;

        Bill::create([
            'bill_number' => 'BILL-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
            'order_id' => $order->id,
            'cashier_id' => Auth::id(),
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'tax_amount' => $taxAmount,
            'service_charge' => $serviceCharge,
            'grand_total' => $grandTotal,
            'payment_method' => 'cash',
            'amount_received' => $grandTotal,
            'change_amount' => 0,
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

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
                'Bill generated and order completed successfully'
            );
    }
}