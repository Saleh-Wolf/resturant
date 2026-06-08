<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('table')
            ->latest()
            ->paginate(10);

        return view(
            'admin.reservations.index',
            compact('reservations')
        );
    }

    public function create()
    {
        $tables = RestaurantTable::all();

        return view(
            'admin.reservations.create',
            compact('tables')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'restaurant_table_id' => ['required', 'exists:restaurant_tables,id'],
            'reservation_date' => ['required', 'date', 'after:now'],
            'guest_count' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $table = RestaurantTable::findOrFail($validated['restaurant_table_id']);
                    // Check for existing reservations for the same table and time
        $existingReservation = Reservation::where(
            'restaurant_table_id',
            $validated['restaurant_table_id']
        )
            ->where(
                'reservation_date',
                $validated['reservation_date']
            )
            ->whereIn('status', [
                'pending',
                'confirmed'
            ])
            ->exists();

        if ($existingReservation) {

            return back()
                ->withErrors([
                    'reservation_date' =>
                    'This table is already reserved at this time.'
                ])
                ->withInput();
        }

        if ($validated['guest_count'] > $table->max_capacity) {
            return back()
                ->withErrors(['guest_count' => 'Guest count exceeds table maximum capacity.'])
                ->withInput();
        }

        Reservation::create([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'restaurant_table_id' => $validated['restaurant_table_id'],
            'reservation_date' => $validated['reservation_date'],
            'guest_count' => $validated['guest_count'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);




        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Reservation created successfully');
    }

    public function confirm(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'confirmed'
        ]);

        return back()
            ->with(
                'success',
                'Reservation confirmed successfully'
            );
    }

    public function cancel(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'cancelled'
        ]);

        return back()
            ->with(
                'success',
                'Reservation cancelled successfully'
            );
    }


    public function edit(Reservation $reservation)
{
    $tables = RestaurantTable::all();

    return view(
        'admin.reservations.edit',
        compact('reservation', 'tables')
    );
}

public function update(Request $request, Reservation $reservation)
{
    $validated = $request->validate([
        'customer_name' => ['required', 'string', 'max:255'],
        'customer_phone' => ['required', 'string', 'max:20'],
        'restaurant_table_id' => ['required', 'exists:restaurant_tables,id'],
        'reservation_date' => ['required', 'date', 'after:now'],
        'guest_count' => ['required', 'integer', 'min:1'],
        'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        'notes' => ['nullable', 'string'],
    ]);

    $table = RestaurantTable::findOrFail($validated['restaurant_table_id']);

    if ($validated['guest_count'] > $table->max_capacity) {
        return back()
            ->withErrors(['guest_count' => 'Guest count exceeds table maximum capacity.'])
            ->withInput();
    }

    $existingReservation = Reservation::where('restaurant_table_id', $validated['restaurant_table_id'])
        ->where('reservation_date', $validated['reservation_date'])
        ->whereIn('status', ['pending', 'confirmed'])
        ->where('id', '!=', $reservation->getKey())
        ->exists();

    if ($existingReservation) {
        return back()
            ->withErrors(['reservation_date' => 'This table is already reserved at this time.'])
            ->withInput();
    }

    $reservation->update($validated);

    return redirect()
        ->route('admin.reservations.index')
        ->with('success', 'Reservation updated successfully');
}

public function destroy(Reservation $reservation)
{
    $reservation->delete();

    return redirect()
        ->route('admin.reservations.index')
        ->with('success', 'Reservation deleted successfully');
}

}
