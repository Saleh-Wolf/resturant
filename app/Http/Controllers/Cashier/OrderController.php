<?php

namespace App\Http\Controllers\Cashier;

use App\Models\Bill;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\BillingService;

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

    public function complete(
        Order $order,
        BillingService $billingService
    ) {
        try {

            $billingService->completeOrder(
                $order,
                Auth::id()
            );

            return back()
                ->with(
                    'success',
                    'Bill generated and order completed successfully'
                );
        } catch (\Exception $exception) {

            return back()
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }
}
