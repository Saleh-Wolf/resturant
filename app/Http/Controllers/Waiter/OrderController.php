<?php

namespace App\Http\Controllers\Waiter;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => [
                'nullable',
                'exists:reservations,id',
            ],

            'order_type' => [
                'required',
                'in:dine_in,takeaway',
            ],

            'restaurant_table_id' => [
                'nullable',
                'required_if:order_type,dine_in',
                'exists:restaurant_tables,id',
            ],

            'items' => [
                'required',
                'array',
            ],
        ]);

        if (!empty($validated['reservation_id'])) {
            $validated['order_type'] = 'dine_in';
        }

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
                'reservation_id' => $validated['reservation_id'] ?? null,
                'order_type' => $validated['order_type'],
                'restaurant_table_id' => $validated['restaurant_table_id'] ?? null,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'subtotal' => 0,
                'total' => 0,
            ]);

            $subtotal = 0;

            foreach ($selectedItems as $menuItemId => $itemData) {
                $menuItem = MenuItem::findOrFail($menuItemId);

                $quantity = (int) $itemData['quantity'];

                $originalUnitPrice = $menuItem->price;
                $unitPrice = $originalUnitPrice;
                $discountAmount = 0;
                $offerId = null;

                $activeOffer = $menuItem->offers()
                    ->where('is_active', true)
                    ->whereDate('start_date', '<=', today())
                    ->whereDate('end_date', '>=', today())
                    ->first();

                if ($activeOffer) {
                    $offerId = $activeOffer->getKey();

                    if ($activeOffer->discount_type === 'percentage') {
                        $discountAmount = ($originalUnitPrice * $activeOffer->discount_value) / 100;
                    } else {
                        $discountAmount = $activeOffer->discount_value;
                    }

                    $discountAmount = min($discountAmount, $originalUnitPrice);

                    $unitPrice = $originalUnitPrice - $discountAmount;
                }

                $totalPrice = $unitPrice * $quantity;

                $order->items()->create([
                    'menu_item_id' => $menuItem->getKey(),
                    'offer_id' => $offerId,
                    'quantity' => $quantity,
                    'original_unit_price' => $originalUnitPrice,
                    'unit_price' => $unitPrice,
                    'discount_amount' => $discountAmount,
                    'total_price' => $totalPrice,
                    'notes' => $itemData['notes'] ?? null,
                ]);

                $subtotal += $totalPrice;
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            if ($validated['order_type'] === 'dine_in' && !empty($validated['restaurant_table_id'])) {
                RestaurantTable::where('id', $validated['restaurant_table_id'])
                    ->update([
                        'status' => 'occupied',
                    ]);
            }

            if (!empty($validated['reservation_id'])) {
                Reservation::where('id', $validated['reservation_id'])
                    ->update([
                        'status' => 'completed',
                    ]);
            }
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
            'reservation',
            'items.menuItem'
        ]);

        return view(
            'waiter.orders.show',
            compact('order')
        );
    }
}