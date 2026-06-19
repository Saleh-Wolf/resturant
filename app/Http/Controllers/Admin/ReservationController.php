<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationController extends Controller
{
   public function index(Request $request)
{
    $query = Reservation::with([
        'table',
        'order'
    ]);

    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('customer_name', 'like', '%' . $request->search . '%')
                ->orWhere('customer_phone', 'like', '%' . $request->search . '%')
                ->orWhere('reservation_number', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('reservation_type')) {
        $query->where('reservation_type', $request->reservation_type);
    }

    if ($request->filled('date')) {
        $query->whereDate('reservation_date', $request->date);
    }

    $reservations = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view(
        'admin.reservations.index',
        compact('reservations')
    );
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'restaurant_table_id' => ['required', 'exists:restaurant_tables,id'],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'reservation_type' => ['required', 'in:immediate,scheduled'],
            'reservation_time' => ['nullable', 'date_format:H:i'],
            'estimated_duration' => ['nullable', 'numeric'],
            'guest_count' => ['required', 'integer', 'min:1'],
            'special_occasion' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $table = RestaurantTable::findOrFail($validated['restaurant_table_id']);

        $existingReservation = Reservation::where('restaurant_table_id', $validated['restaurant_table_id'])
            ->where('reservation_date', $validated['reservation_date'])
            ->where('reservation_time', $validated['reservation_time'] ?? null)
            ->whereIn('status', [
                'confirmed',
                'arrived',
            ])
            ->exists();

        if ($existingReservation) {
            return back()
                ->withErrors([
                    'reservation_date' => 'This table is already reserved at this date and time.',
                ])
                ->withInput();
        }

        if ($validated['guest_count'] > $table->max_capacity) {
            return back()
                ->withErrors([
                    'guest_count' => 'Guest count exceeds table maximum capacity.',
                ])
                ->withInput();
        }




        Reservation::create([
            'reservation_number' => 'RES-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
            'reservation_type' => $validated['reservation_type'],
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'restaurant_table_id' => $validated['restaurant_table_id'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'] ?? null,
            'estimated_duration' => $validated['estimated_duration'] ?? null,
            'guest_count' => $validated['guest_count'],
            'special_occasion' => $validated['special_occasion'] ?? null,
            'status' => 'confirmed',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Reservation created successfully');
    }

    public function confirm(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'confirmed',
        ]);

        return back()
            ->with('success', 'Reservation confirmed successfully');
    }

    public function cancel(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'cancelled',
        ]);

        return back()
            ->with('success', 'Reservation cancelled successfully');
    }

    public function arrived(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'arrived',
        ]);

        return back()
            ->with('success', 'Reservation marked as arrived successfully');
    }

    public function noShow(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'no_show',
        ]);

        return back()
            ->with('success', 'Reservation marked as no-show successfully');
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
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'reservation_type' => ['required', 'in:immediate,scheduled'],
            'reservation_time' => ['nullable', 'date_format:H:i'],
            'estimated_duration' => ['nullable', 'numeric'],
            'guest_count' => ['required', 'integer', 'min:1'],
            'special_occasion' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:confirmed,arrived,completed,cancelled,no_show'],
            'notes' => ['nullable', 'string'],
        ]);

        $table = RestaurantTable::findOrFail($validated['restaurant_table_id']);

        if ($validated['guest_count'] > $table->max_capacity) {
            return back()
                ->withErrors([
                    'guest_count' => 'Guest count exceeds table maximum capacity.',
                ])
                ->withInput();
        }

        $existingReservation = Reservation::where('restaurant_table_id', $validated['restaurant_table_id'])
            ->where('reservation_date', $validated['reservation_date'])
            ->where('reservation_time', $validated['reservation_time'] ?? null)
            ->whereIn('status', [
                'confirmed',
                'arrived',
            ])
            ->where('id', '!=', $reservation->getKey())
            ->exists();

        if ($existingReservation) {
            return back()
                ->withErrors([
                    'reservation_date' => 'This table is already reserved at this date and time.',
                ])
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

    public function show(Reservation $reservation)
{
    $reservation->load([
        'table',
        'order',
    ]);

    return view(
        'admin.reservations.show',
        compact('reservation')
    );
}
}
