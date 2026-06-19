<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Bill;
use App\Models\Ingredient;
use App\Models\Offer;
use App\Models\RestaurantTable;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $query = Bill::with([
            'order.table',
            'order.waiter',
            'cashier',
        ])
            ->where('payment_status', 'paid');

        if ($request->filled('from_date')) {
            $query->whereDate('paid_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('paid_at', '<=', $request->to_date);
        }

        $bills = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $summaryQuery = Bill::where('payment_status', 'paid');

        if ($request->filled('from_date')) {
            $summaryQuery->whereDate('paid_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $summaryQuery->whereDate('paid_at', '<=', $request->to_date);
        }

        $totalSales = $summaryQuery->sum('grand_total');

        $completedOrdersCount = $summaryQuery->count();

        $averageOrderValue = $completedOrdersCount > 0
            ? $totalSales / $completedOrdersCount
            : 0;

        $totalDiscount = $summaryQuery->sum('discount_total');

        $cashSales = (clone $summaryQuery)
            ->where('payment_method', 'cash')
            ->sum('grand_total');

        $cardSales = (clone $summaryQuery)
            ->where('payment_method', 'card')
            ->sum('grand_total');

        return view(
            'admin.reports.sales',
            compact(
                'bills',
                'totalSales',
                'completedOrdersCount',
                'averageOrderValue',
                'totalDiscount',
                'cashSales',
                'cardSales'
            )
        );
    }
    public function orders(Request $request)
    {
        $query = Order::with([
            'table',
            'waiter',
            'bill'
        ]);

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $orders = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.reports.orders',
            compact('orders')
        );
    }
    public function reservations(Request $request)
    {
        $query = Reservation::with('table');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalReservations = Reservation::count();

        $confirmedReservations = Reservation::where('status', 'confirmed')->count();

        $completedReservations = Reservation::where('status', 'completed')->count();

        $cancelledReservations = Reservation::whereIn('status', [
            'cancelled',
            'no_show',
        ])->count();

        return view(
            'admin.reports.reservations',
            compact(
                'reservations',
                'totalReservations',
                'confirmedReservations',
                'completedReservations',
                'cancelledReservations'
            )
        );
    }


    public function offersPerformance(Request $request)
{
    $offers = Offer::withCount('menuItems')
        ->with([
            'menuItems'
        ])
        ->get();

    $offerStats = $offers->map(function ($offer) use ($request) {
        $query = OrderItem::where('offer_id', $offer->id);

        if ($request->filled('from_date')) {
            $query->whereHas('order.bill', function ($q) use ($request) {
                $q->whereDate('paid_at', '>=', $request->from_date);
            });
        }

        if ($request->filled('to_date')) {
            $query->whereHas('order.bill', function ($q) use ($request) {
                $q->whereDate('paid_at', '<=', $request->to_date);
            });
        }

        $timesUsed = $query->count();
        $totalDiscount = $query->sum(DB::raw('discount_amount * quantity'));
        $revenue = $query->sum('total_price');

        return [
            'offer' => $offer,
            'items_count' => $offer->menu_items_count,
            'times_used' => $timesUsed,
            'total_discount' => $totalDiscount,
            'revenue' => $revenue,
        ];
    });

    return view(
        'admin.reports.offers-performance',
        compact('offerStats')
    );
}

    public function topSellingItems()
    {
        $items = OrderItem::select(
            'menu_item_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(total_price) as total_revenue'),
            DB::raw('AVG(unit_price) as average_price')
        )
            ->with('menuItem')
            ->groupBy('menu_item_id')
            ->orderByDesc('total_quantity')
            ->paginate(15);

        return view(
            'admin.reports.top-selling-items',
            compact('items')
        );
    }

    public function lowStock()
{
    $ingredients = Ingredient::orderBy('name')
        ->paginate(15);

    return view(
        'admin.reports.low-stock',
        compact('ingredients')
    );
}

    public function stockMovements()
{
    $movements = StockMovement::with('ingredient')
        ->latest()
        ->paginate(20);

    return view(
        'admin.reports.stock-movements',
        compact('movements')
    );
}

public function tableUtilization(Request $request)
{
    $tables = RestaurantTable::withCount([
        'orders',
        'reservations',
    ])
        ->withSum('orders as revenue_generated', 'total')
        ->orderBy('table_number')
        ->paginate(15);

    return view(
        'admin.reports.table-utilization',
        compact('tables')
    );
}

}
