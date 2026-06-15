<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'current_stock',
        'minimum_stock',
        'cost_per_unit',
        'is_active',
    ];

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_ingredients')
            ->withPivot('quantity_required')
            ->withTimestamps();
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->minimum_stock;
    }
}