<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'ingredient_id',
        'type',
        'quantity',
        'reference_type',
        'reference_id',
        'notes',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}