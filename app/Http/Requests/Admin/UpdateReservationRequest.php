<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'customer_phone' => [
                'required',
                'string',
                'max:20',
            ],

            'restaurant_table_id' => [
                'required',
                'exists:restaurant_tables,id',
            ],

            'reservation_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'reservation_type' => [
                'required',
                'in:immediate,scheduled',
            ],

            'reservation_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'estimated_duration' => [
                'nullable',
                'numeric',
            ],

            'guest_count' => [
                'required',
                'integer',
                'min:1',
            ],

            'special_occasion' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:confirmed,arrived,completed,cancelled,no_show',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}