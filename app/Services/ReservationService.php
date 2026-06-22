<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\RestaurantTable;
use Exception;

class ReservationService
{
    public function create(array $data)
    {
        $table = RestaurantTable::findOrFail(
            $data['restaurant_table_id']
        );

        $existingReservation = Reservation::where(
            'restaurant_table_id',
            $data['restaurant_table_id']
        )
            ->where(
                'reservation_date',
                $data['reservation_date']
            )
            ->where(
                'reservation_time',
                $data['reservation_time'] ?? null
            )
            ->whereIn('status', [
                'confirmed',
                'arrived',
            ])
            ->exists();

        if ($existingReservation) {
            throw new Exception(
                'This table is already reserved at this date and time.'
            );
        }

        if ($data['guest_count'] > $table->max_capacity) {
            throw new Exception(
                'Guest count exceeds table maximum capacity.'
            );
        }

        return Reservation::create([
            'reservation_number' =>
                'RES-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(substr(uniqid(), -4)),

            'reservation_type' => $data['reservation_type'],
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'restaurant_table_id' => $data['restaurant_table_id'],
            'reservation_date' => $data['reservation_date'],
            'reservation_time' => $data['reservation_time'] ?? null,
            'estimated_duration' => $data['estimated_duration'] ?? null,
            'guest_count' => $data['guest_count'],
            'special_occasion' => $data['special_occasion'] ?? null,
            'status' => 'confirmed',
            'notes' => $data['notes'] ?? null,
        ]);
    }

   public function update(Reservation $reservation, array $data): Reservation
{
    $table = RestaurantTable::findOrFail(
        $data['restaurant_table_id']
    );

    if ($data['guest_count'] > $table->max_capacity) {
        throw new Exception(
            'Guest count exceeds table maximum capacity.'
        );
    }

    $existingReservation = Reservation::where(
        'restaurant_table_id',
        $data['restaurant_table_id']
    )
        ->where(
            'reservation_date',
            $data['reservation_date']
        )
        ->where(
            'reservation_time',
            $data['reservation_time'] ?? null
        )
        ->whereIn('status', [
            'confirmed',
            'arrived',
        ])
        ->where(
            'id',
            '!=',
            $reservation->getKey()
        )
        ->exists();

    if ($existingReservation) {
        throw new Exception(
            'This table is already reserved at this date and time.'
        );
    }

    $reservation->update($data);

    return $reservation;
}
public function confirm(Reservation $reservation): Reservation
{
    $reservation->update([
        'status' => 'confirmed',
    ]);

    return $reservation;
}

public function cancel(Reservation $reservation): Reservation
{
    $reservation->update([
        'status' => 'cancelled',
    ]);

    return $reservation;
}

public function markArrived(Reservation $reservation): Reservation
{
    $reservation->update([
        'status' => 'arrived',
    ]);

    return $reservation;
}

public function markNoShow(Reservation $reservation): Reservation
{
    $reservation->update([
        'status' => 'no_show',
    ]);

    return $reservation;
}



}