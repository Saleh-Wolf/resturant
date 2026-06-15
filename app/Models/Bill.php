<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'bill_number',
        'order_id',
        'cashier_id',
        'subtotal',
        'discount_total',
        'tax_amount',
        'service_charge',
        'grand_total',
        'payment_method',
        'amount_received',
        'change_amount',
        'payment_status',
        'paid_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}