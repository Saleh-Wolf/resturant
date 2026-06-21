<?php

namespace App\Http\Requests\Waiter;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reservation_id' => [
                'nullable',
                'exists:reservations,id',
            ],

            'order_type' => [
                'required',
                'in:dine_in,takeaway',
            ],

            'customer_name' => [
                'nullable',
                'required_without:reservation_id',
                'string',
                'max:255',
            ],

            'customer_phone' => [
                'nullable',
                'required_without:reservation_id',
                'string',
                'max:20',
            ],

            'guest_count' => [
                'nullable',
                'required_without:reservation_id',
                'integer',
                'min:1',
            ],

            'restaurant_table_id' => [
                'nullable',
                'required_if:order_type,dine_in',
                'exists:restaurant_tables,id',
            ],

            'items' => [
                'required',
                'array',
            ],
        ];
    }
}