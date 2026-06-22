<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Order;
use App\Models\RestaurantTable;
use Exception;
use Illuminate\Support\Facades\DB;

class BillingService
{
    public function completeOrder(Order $order, int $cashierId): Bill
    {
        if ($order->bill) {
            throw new Exception('This order already has a bill.');
        }

        return DB::transaction(function () use ($order, $cashierId) {

            $order->load([
                'items',
                'table',
                'waiter',
                'bill',
            ]);

            $subtotal = $order->items->sum(function ($item) {
                return $item->original_unit_price * $item->quantity;
            });

            $discountTotal = $order->items->sum(function ($item) {
                return $item->discount_amount * $item->quantity;
            });

            $taxAmount = 0;
            $serviceCharge = 0;
            $grandTotal = $order->total;

            $bill = Bill::create([
                'bill_number' => 'BILL-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
                'order_id' => $order->id,
                'cashier_id' => $cashierId,
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'tax_amount' => $taxAmount,
                'service_charge' => $serviceCharge,
                'grand_total' => $grandTotal,
                'payment_method' => 'cash',
                'amount_received' => $grandTotal,
                'change_amount' => 0,
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            $order->update([
                'status' => 'completed',
            ]);

            if ($order->order_type === 'dine_in' && $order->restaurant_table_id) {
                RestaurantTable::where('id', $order->restaurant_table_id)
                    ->update([
                        'status' => 'available',
                    ]);
            }

            return $bill;
        });
    }
    public function voidBill(Bill $bill): Bill
    {
        if ($bill->payment_status === 'cancelled') {
            throw new \Exception('This bill is already cancelled.');
        }

        $bill->update([
            'payment_status' => 'cancelled',
        ]);

        return $bill;
    }
}
