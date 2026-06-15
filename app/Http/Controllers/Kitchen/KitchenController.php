<?php

namespace App\Http\Controllers\Kitchen;

use App\Models\Order;
use App\Http\Controllers\Controller;

class KitchenController extends Controller
{
    public function index()
    {
        $pendingOrders = Order::with([
            'table',
            'items.menuItem'
        ])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $preparingOrders = Order::with([
            'table',
            'items.menuItem'
        ])
            ->where('status', 'preparing')
            ->latest()
            ->get();

        return view(
            'kitchen.index',
            compact(
                'pendingOrders',
                'preparingOrders'
            )
        );
    }
}