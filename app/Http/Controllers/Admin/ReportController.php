<?php

namespace App\Http\Controllers\Admin;


use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Bill;
use App\Models\Ingredient;
use App\Models\Offer;
use App\Models\RestaurantTable;
use App\Models\StockMovement;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\SalesReportExport;
use App\Exports\OffersPerformanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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

        if ($request->input('sort') === 'usage') {
            $offerStats = $offerStats->sortByDesc('times_used');
        } elseif ($request->input('sort') === 'discount') {
            $offerStats = $offerStats->sortByDesc('total_discount');
        } elseif ($request->input('sort') === 'revenue') {
            $offerStats = $offerStats->sortByDesc('revenue');
        }

        $totalUsage = $offerStats->sum('times_used');

        $totalDiscount = $offerStats->sum('total_discount');

        $totalRevenue = $offerStats->sum('revenue');

        $totalOffers = $offerStats->count();

        return view(
            'admin.reports.offers-performance',
            compact(
                'offerStats',
                'totalUsage',
                'totalDiscount',
                'totalRevenue',
                'totalOffers'
            )
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
    $query = RestaurantTable::with([
        'orders.bill',
    ]);

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    $tables = $query
        ->get()
        ->map(function ($table) use ($request) {

            $orders = $table->orders;

            if ($request->filled('from_date')) {
                $orders = $orders->filter(function ($order) use ($request) {
                    return $order->created_at->toDateString() >= $request->from_date;
                });
            }

            if ($request->filled('to_date')) {
                $orders = $orders->filter(function ($order) use ($request) {
                    return $order->created_at->toDateString() <= $request->to_date;
                });
            }

            $completedOrders = $orders->filter(function ($order) {
                return $order->bill && $order->bill->paid_at;
            });

            $totalMinutesOccupied = $completedOrders->sum(function ($order) {
                return $order->created_at->diffInMinutes($order->bill->paid_at);
            });

            $totalHoursOccupied = $totalMinutesOccupied / 60;

            $averageDurationMinutes = $completedOrders->count() > 0
                ? $totalMinutesOccupied / $completedOrders->count()
                : 0;

            $daysCount = 1;

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $daysCount = \Carbon\Carbon::parse($request->from_date)
                        ->diffInDays(\Carbon\Carbon::parse($request->to_date)) + 1;
            }

            $availableHours = $daysCount * 12;

            $utilizationRate = $availableHours > 0
                ? ($totalHoursOccupied / $availableHours) * 100
                : 0;

            $table->orders_count_filtered = $orders->count();
            $table->revenue_generated_filtered = $orders->sum('total');
            $table->total_hours_occupied = $totalHoursOccupied;
            $table->average_duration_minutes = $averageDurationMinutes;
            $table->utilization_rate = $utilizationRate;

            return $table;
        });

    if ($request->input('sort') === 'revenue') {
        $tables = $tables->sortByDesc('revenue_generated_filtered');
    } elseif ($request->input('sort') === 'orders') {
        $tables = $tables->sortByDesc('orders_count_filtered');
    } elseif ($request->input('sort') === 'utilization') {
        $tables = $tables->sortByDesc('utilization_rate');
    } else {
        $tables = $tables->sortBy('table_number');
    }

  $tables = $tables->values();

$mostUsedTables = $tables
    ->sortByDesc('orders_count_filtered')
    ->take(5);

$leastUsedTables = $tables
    ->sortBy('orders_count_filtered')
    ->take(5);

return view(
    'admin.reports.table-utilization',
    compact(
        'tables',
        'mostUsedTables',
        'leastUsedTables'
    )
);
}
    public function monthlySales(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = Carbon::parse($month . '-01')->endOfMonth();

        $previousStart = $startOfMonth->copy()->subMonth()->startOfMonth();
        $previousEnd = $startOfMonth->copy()->subMonth()->endOfMonth();

        $billsQuery = Bill::where('payment_status', 'paid')
            ->whereBetween('paid_at', [
                $startOfMonth,
                $endOfMonth,
            ]);

        $totalRevenue = (clone $billsQuery)->sum('grand_total');

        $totalOrders = (clone $billsQuery)->count();

        $averageOrderValue = $totalOrders > 0
            ? $totalRevenue / $totalOrders
            : 0;

        $averageDailyRevenue = $totalRevenue / $startOfMonth->daysInMonth;

        $previousRevenue = Bill::where('payment_status', 'paid')
            ->whereBetween('paid_at', [
                $previousStart,
                $previousEnd,
            ])
            ->sum('grand_total');

        $growthPercentage = $previousRevenue > 0
            ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100
            : 0;

        $dailySales = Bill::selectRaw('DATE(paid_at) as date, SUM(grand_total) as revenue, COUNT(*) as orders_count')
            ->where('payment_status', 'paid')
            ->whereBetween('paid_at', [
                $startOfMonth,
                $endOfMonth,
            ])
            ->groupByRaw('DATE(paid_at)')
            ->orderBy('date')
            ->get();

        $topItems = OrderItem::select(
            'menu_item_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(total_price) as total_revenue')
        )
            ->whereHas('order.bill', function ($query) use ($startOfMonth, $endOfMonth) {
                $query->where('payment_status', 'paid')
                    ->whereBetween('paid_at', [
                        $startOfMonth,
                        $endOfMonth,
                    ]);
            })
            ->with('menuItem')
            ->groupBy('menu_item_id')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();

        return view(
            'admin.reports.monthly-sales',
            compact(
                'month',
                'totalRevenue',
                'totalOrders',
                'averageOrderValue',
                'averageDailyRevenue',
                'previousRevenue',
                'growthPercentage',
                'dailySales',
                'topItems'
            )
        );
    }


    public function exportSalesExcel(Request $request)
{
    return Excel::download(
        new SalesReportExport(
            $request->from_date,
            $request->to_date
        ),
        'sales-report.xlsx'
    );
}

public function exportSalesPdf(Request $request)
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
        ->get();

    $totalSales = $bills->sum('grand_total');
    $completedOrdersCount = $bills->count();

    $averageOrderValue = $completedOrdersCount > 0
        ? $totalSales / $completedOrdersCount
        : 0;

    $pdf = Pdf::loadView(
        'admin.reports.exports.sales-pdf',
        compact(
            'bills',
            'totalSales',
            'completedOrdersCount',
            'averageOrderValue'
        )
    );

    return $pdf->download('sales-report.pdf');
}



public function popularItems(Request $request)
{
    $items = MenuItem::with([
        'category',
        'subcategory',
    ])
        ->withCount([
            'orderItems as times_ordered' => function ($query) use ($request) {
                $this->applyPopularItemsDateFilter($query, $request);
            },
        ])
        ->withSum([
            'orderItems as total_quantity_sold' => function ($query) use ($request) {
                $this->applyPopularItemsDateFilter($query, $request);
            },
        ], 'quantity')
        ->withSum([
            'orderItems as revenue_generated' => function ($query) use ($request) {
                $this->applyPopularItemsDateFilter($query, $request);
            },
        ], 'total_price');

    if ($request->filled('category_id')) {
        $items->where('category_id', $request->category_id);
    }

    if ($request->filled('subcategory_id')) {
        $items->where('subcategory_id', $request->subcategory_id);
    }

    if ($request->input('sort') === 'orders') {
        $items->orderByDesc('times_ordered');
    } elseif ($request->input('sort') === 'quantity') {
        $items->orderByDesc('total_quantity_sold');
    } elseif ($request->input('sort') === 'revenue') {
        $items->orderByDesc('revenue_generated');
    } else {
        $items->orderBy('name');
    }

    $items = $items
        ->paginate(15)
        ->withQueryString();

    $totalRevenue = OrderItem::query()
        ->when($request->filled('from_date') || $request->filled('to_date'), function ($query) use ($request) {
            $this->applyPopularItemsDateFilter($query, $request);
        })
        ->sum('total_price');

    $bestSellers = MenuItem::withSum('orderItems as total_quantity_sold', 'quantity')
        ->orderByDesc('total_quantity_sold')
        ->take(10)
        ->get();

    $slowMovingItems = MenuItem::withSum('orderItems as total_quantity_sold', 'quantity')
        ->havingRaw('total_quantity_sold > 0')
        ->orderBy('total_quantity_sold')
        ->take(10)
        ->get();

    $neverOrderedItems = MenuItem::doesntHave('orderItems')
        ->orderBy('name')
        ->take(10)
        ->get();

    return view(
        'admin.reports.popular-items',
        compact(
            'items',
            'totalRevenue',
            'bestSellers',
            'slowMovingItems',
            'neverOrderedItems'
        )
    );
}

private function applyPopularItemsDateFilter($query, Request $request): void
{
    $query->whereHas('order.bill', function ($billQuery) use ($request) {
        $billQuery->where('payment_status', 'paid');

        if ($request->filled('from_date')) {
            $billQuery->whereDate('paid_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $billQuery->whereDate('paid_at', '<=', $request->to_date);
        }
    });
}

public function exportOffersPerformanceExcel(Request $request)
{
    return Excel::download(
        new OffersPerformanceExport(
            $request->from_date,
            $request->to_date,
            $request->sort
        ),
        'offers-performance-report.xlsx'
    );
}

public function exportOffersPerformancePdf(Request $request)
{
    $offers = Offer::withCount('menuItems')->get();

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

        return [
            'offer' => $offer,
            'items_count' => $offer->menu_items_count,
            'times_used' => $query->count(),
            'total_discount' => $query->sum(DB::raw('discount_amount * quantity')),
            'revenue' => $query->sum('total_price'),
        ];
    });

    $pdf = Pdf::loadView(
        'admin.reports.exports.offers-performance-pdf',
        compact('offerStats')
    );

    return $pdf->download('offers-performance-report.pdf');
}

}
