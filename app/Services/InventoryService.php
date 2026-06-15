<?php

namespace App\Services;

use App\Models\Order;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryService
{
    public function deductForOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {

            $order->load([
                'items.menuItem.ingredients',
            ]);

            foreach ($order->items as $orderItem) {
                $menuItem = $orderItem->menuItem;

                foreach ($menuItem->ingredients as $ingredient) {
                    $requiredQuantity = $ingredient->pivot->quantity_required * $orderItem->quantity;

                    if ($ingredient->current_stock < $requiredQuantity) {
                        throw new RuntimeException(
                            'Not enough stock for ingredient: ' . $ingredient->name
                        );
                    }

                    $ingredient->decrement(
                        'current_stock',
                        $requiredQuantity
                    );

                    StockMovement::create([
                        'ingredient_id' => $ingredient->id,
                        'type' => 'deduction',
                        'quantity' => $requiredQuantity,
                        'reference_type' => Order::class,
                        'reference_id' => $order->id,
                        'notes' => 'Stock deducted for Order #' . $order->id,
                    ]);
                }
            }
        });
    }
}