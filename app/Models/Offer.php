<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'name',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'is_active',
        'display_on_menu',
    ];

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class);
    }
}