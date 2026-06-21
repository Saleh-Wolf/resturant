<?php

namespace App\Http\Controllers\Waiter;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Waiter\StoreOrderRequest;
use App\Services\OrderService;
use Exception;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'table',
            'waiter',
            'reservation',
        ])
            ->latest()
            ->paginate(10);

        return view(
            'waiter.orders.index',
            compact('orders')
        );
    }

    public function create(Request $request)
    {
        $reservation = null;

        if ($request->filled('reservation_id')) {
            $reservation = Reservation::with('table')
                ->findOrFail($request->reservation_id);
        }

        $tables = RestaurantTable::where('status', 'available');

        if ($reservation) {
            $tables->orWhere('id', $reservation->restaurant_table_id);
        }

        $tables = $tables->get();

        $menuItems = MenuItem::where('is_available', true)->get();

        return view(
            'waiter.orders.create',
            compact('tables', 'menuItems', 'reservation')
        );
    }

    public function store(
    StoreOrderRequest $request,
    OrderService $orderService
) {
    try {
        $orderService->create(
            $request->validated(),
            Auth::id()
        );

        return redirect()
            ->route('waiter.orders.index')
            ->with('success', 'Order created successfully');

    } catch (Exception $exception) {
        return back()
            ->withErrors([
                'items' => $exception->getMessage(),
            ])
            ->withInput();
    }
}
    public function show(Order $order)
    {
        $order->load([
            'table',
            'waiter',
            'reservation',
            'items.menuItem'
        ]);

        return view(
            'waiter.orders.show',
            compact('order')
        );
    }
}
