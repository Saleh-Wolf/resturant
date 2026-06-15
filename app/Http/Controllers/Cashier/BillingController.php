<?php

namespace App\Http\Controllers\Cashier;

use App\Models\Order;
use App\Http\Controllers\Controller;

class BillingController extends Controller
{
    public function index()
    {
        $readyOrders = Order::with([
            'table',
            'waiter',
            'items.menuItem',
            'items.offer',
        ])
            ->where('status', 'ready')
            ->latest()
            ->get();

        $pendingBillsCount = $readyOrders->count();

        $todayRevenue = 0;

        return view(
            'cashier.dashboard',
            compact(
                'readyOrders',
                'pendingBillsCount',
                'todayRevenue'
            )
        );
    }
}