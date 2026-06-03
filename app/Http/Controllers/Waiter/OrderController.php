<?php

namespace App\Http\Controllers\Waiter;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['table', 'waiter'])
            ->latest()
            ->paginate(10);

        return view(
            'waiter.orders.index',
            compact('orders')
        );
    }

    public function create()
    {
        $tables = RestaurantTable::where(
            'status',
            'available'
        )->get();

        $menuItems = MenuItem::where(
            'is_available',
            true
        )->get();

        return view(
            'waiter.orders.create',
            compact(
                'tables',
                'menuItems'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_table_id' => [
                'required',
                'exists:restaurant_tables,id',
            ],

            'items' => [
                'required',
                'array',
            ],
        ]);

        $selectedItems = collect($request->items)
            ->filter(function ($item) {
                return isset($item['quantity']) && (int) $item['quantity'] > 0;
            });

        if ($selectedItems->isEmpty()) {
            return back()
                ->withErrors(['items' => 'Please select at least one menu item.'])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $selectedItems) {

            $order = Order::create([
                'restaurant_table_id' => $validated['restaurant_table_id'],
                'user_id' => Auth::id(),
                'status' => 'pending',
                'subtotal' => 0,
                'total' => 0,
            ]);

            $subtotal = 0;

            foreach ($selectedItems as $menuItemId => $itemData) {
                $menuItem = MenuItem::findOrFail($menuItemId);

                $quantity = (int) $itemData['quantity'];
                $unitPrice = $menuItem->price;
                $totalPrice = $unitPrice * $quantity;

                $order->items()->create([
                    'menu_item_id' => $menuItem->getKey(),
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'notes' => $itemData['notes'] ?? null,
                ]);

                $subtotal += $totalPrice;
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            RestaurantTable::where('id', $validated['restaurant_table_id'])
                ->update([
                    'status' => 'occupied',
                ]);
        });

        return redirect()
            ->route('waiter.orders.index')
            ->with('success', 'Order created successfully');
    }
    public function show(Order $order)
    {
        $order->load([
            'table',
            'waiter',
            'items.menuItem'
        ]);

        return view(
            'waiter.orders.show',
            compact('order')
        );
    }
}
