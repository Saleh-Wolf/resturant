<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
protected $fillable = [
    'reservation_id',
    'order_type',
    'restaurant_table_id',
    'user_id',
    'status',
    'subtotal',
    'total',
    'notes',
];

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }
}
