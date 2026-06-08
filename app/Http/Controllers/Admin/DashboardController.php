<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = Order::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total');

        $todayOrders = Order::whereDate('created_at', today())
            ->count();

        $pendingReservations = Reservation::where('status', 'pending')
            ->count();

        $availableTables = RestaurantTable::where('status', 'available')
            ->count();

        $occupiedTables = RestaurantTable::where('status', 'occupied')
            ->count();

        return view(
            'admin.dashboard',
            compact(
                'todaySales',
                'todayOrders',
                'pendingReservations',
                'availableTables',
                'occupiedTables'
            )
        );
    }
}