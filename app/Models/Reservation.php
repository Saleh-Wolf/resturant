<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'reservation_number',
        'reservation_type',
        'restaurant_table_id',
        'customer_name',
        'customer_phone',
        'reservation_date',
        'reservation_time',
        'estimated_duration',
        'guest_count',
        'special_occasion',
        'status',
        'notes',
    ];

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}