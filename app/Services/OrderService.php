<?php

namespace App\Services;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function create(array $data, int $userId): Order
    {
        if (!empty($data['reservation_id'])) {
            $reservation = Reservation::findOrFail(
                $data['reservation_id']
            );

            $data['order_type'] = 'dine_in';
            $data['customer_name'] = $reservation->customer_name;
            $data['customer_phone'] = $reservation->customer_phone;
            $data['guest_count'] = $reservation->guest_count;
            $data['restaurant_table_id'] = $reservation->restaurant_table_id;
        }

        $selectedItems = collect($data['items'] ?? [])
            ->filter(function ($item) {
                return isset($item['quantity'])
                    && (int) $item['quantity'] > 0;
            });

        if ($selectedItems->isEmpty()) {
            throw new Exception(
                'Please select at least one menu item.'
            );
        }

        return DB::transaction(function () use ($data, $userId, $selectedItems) {

            $order = Order::create([
                'reservation_id' => $data['reservation_id'] ?? null,
                'order_type' => $data['order_type'],
                'customer_name' => $data['customer_name'] ?? null,
                'customer_phone' => $data['customer_phone'] ?? null,
                'guest_count' => $data['guest_count'] ?? null,
                'restaurant_table_id' => $data['restaurant_table_id'] ?? null,
                'user_id' => $userId,
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

            if ($data['order_type'] === 'dine_in' && !empty($data['restaurant_table_id'])) {
                RestaurantTable::where('id', $data['restaurant_table_id'])
                    ->update([
                        'status' => 'occupied',
                    ]);
            }

            if (!empty($data['reservation_id'])) {
                Reservation::where('id', $data['reservation_id'])
                    ->update([
                        'status' => 'completed',
                    ]);
            }

            return $order;
        });
    }
}