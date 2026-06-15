<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    protected $fillable = [
        'table_number',
        'type',
        'min_capacity',
        'max_capacity',
        'location',
        'status',
        'notes',
        'qr_token',
    ];


    public function orders()
    {
        return $this->hasMany(Order::class, 'restaurant_table_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'restaurant_table_id');
    }
}
