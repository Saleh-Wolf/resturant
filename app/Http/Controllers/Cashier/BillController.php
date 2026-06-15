<?php

namespace App\Http\Controllers\Cashier;

use App\Models\Bill;
use App\Http\Controllers\Controller;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::with([
            'order.table',
            'order.waiter',
            'cashier',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'cashier.bills.index',
            compact('bills')
        );
    }

    public function show(Bill $bill)
    {
        $bill->load([
            'order.table',
            'order.waiter',
            'order.items.menuItem',
            'order.items.offer',
            'cashier',
        ]);

        return view(
            'cashier.bills.show',
            compact('bill')
        );
    }
}