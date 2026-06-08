<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $query = Order::with(['table', 'waiter'])
            ->where('status', 'completed');

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $summaryQuery = Order::where('status', 'completed');

        if ($request->filled('from_date')) {
            $summaryQuery->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $summaryQuery->whereDate('created_at', '<=', $request->to_date);
        }

        $totalSales = $summaryQuery->sum('total');

        $completedOrdersCount = $summaryQuery->count();

        $averageOrderValue = $completedOrdersCount > 0
            ? $totalSales / $completedOrdersCount
            : 0;

        return view(
            'admin.reports.sales',
            compact(
                'orders',
                'totalSales',
                'completedOrdersCount',
                'averageOrderValue'
            )
        );
    }
}