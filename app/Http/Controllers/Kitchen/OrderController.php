<?php

namespace App\Http\Controllers\Kitchen;

use App\Models\Order;
use App\Services\InventoryService;
use App\Http\Controllers\Controller;
use RuntimeException;

class OrderController extends Controller
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

        $readyOrders = Order::with([
            'table',
            'items.menuItem'
        ])
            ->where('status', 'ready')
            ->latest()
            ->get();

        return view(
            'kitchen.orders.index',
            compact(
                'pendingOrders',
                'preparingOrders',
                'readyOrders'
            )
        );
    }

    public function startPreparing(Order $order, InventoryService $inventoryService)
    {
        if ($order->status !== 'pending') {
            return back()
                ->with('error', 'Only pending orders can be started.');
        }

        try {
            $inventoryService->deductForOrder($order);

            $order->update([
                'status' => 'preparing',
            ]);

            return back()
                ->with('success', 'Order is now preparing and stock has been deducted.');

        } catch (RuntimeException $exception) {
            return back()
                ->with('error', $exception->getMessage());
        }
    }

    public function markReady(Order $order)
    {
        if ($order->status !== 'preparing') {
            return back()
                ->with('error', 'Only preparing orders can be marked as ready.');
        }

        $order->update([
            'status' => 'ready',
        ]);

        return back()
            ->with('success', 'Order is ready');
    }
}