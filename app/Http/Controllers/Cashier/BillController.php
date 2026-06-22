<?php

namespace App\Http\Controllers\Cashier;

use App\Models\Bill;
use App\Services\BillingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with([
            'order.table',
            'order.waiter',
            'cashier',
        ]);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('bill_number', 'like', '%' . $request->search . '%')
                    ->orWhere('order_id', $request->search)
                    ->orWhereHas('order', function ($orderQuery) use ($request) {
                        $orderQuery->where('customer_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('paid_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('paid_at', '<=', $request->to_date);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('waiter_id')) {
            $query->whereHas('order', function ($orderQuery) use ($request) {
                $orderQuery->where('user_id', $request->waiter_id);
            });
        }

        $bills = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

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

    public function void(
    Bill $bill,
    BillingService $billingService
) {
    try {

        $billingService->voidBill($bill);

        return back()
            ->with(
                'success',
                'Bill cancelled successfully'
            );

    } catch (Exception $exception) {

        return back()
            ->with(
                'error',
                $exception->getMessage()
            );
    }
}
}