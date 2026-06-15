<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bill;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = Bill::where('payment_status', 'paid')
            ->whereDate('paid_at', today())
            ->sum('grand_total');

        $todayOrders = Order::whereDate('created_at', today())
            ->count();

        $confirmedReservations = Reservation::where('status', 'confirmed')
            ->count();

        $arrivedReservations = Reservation::where('status', 'arrived')
            ->count();

        $availableTables = RestaurantTable::where('status', 'available')
            ->count();

        $occupiedTables = RestaurantTable::where('status', 'occupied')
            ->count();

        $pendingOrders = Order::where('status', 'pending')
            ->count();

        $readyOrders = Order::where('status', 'ready')
            ->count();

        $recentOrders = Order::with([
            'table',
            'waiter',
            'bill',
        ])
            ->latest()
            ->take(10)
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'todaySales',
                'todayOrders',
                'confirmedReservations',
                'arrivedReservations',
                'availableTables',
                'occupiedTables',
                'pendingOrders',
                'readyOrders',
                'recentOrders'
            )
        );
    }
}