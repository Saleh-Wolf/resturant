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
}
