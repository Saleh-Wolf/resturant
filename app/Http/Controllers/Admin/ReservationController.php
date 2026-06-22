<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReservationRequest;
use App\Http\Requests\Admin\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use App\Services\ReservationService;
use Exception;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with([
            'table',
            'order',
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

    public function create()
    {
        $tables = RestaurantTable::all();

        return view(
            'admin.reservations.create',
            compact('tables')
        );
    }

    public function store(
        StoreReservationRequest $request,
        ReservationService $reservationService
    ) {
        try {
            $reservationService->create(
                $request->validated()
            );

            return redirect()
                ->route('admin.reservations.index')
                ->with('success', 'Reservation created successfully');
        } catch (Exception $exception) {
            return back()
                ->withErrors([
                    'reservation_date' => $exception->getMessage(),
                ])
                ->withInput();
        }
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

    public function edit(Reservation $reservation)
    {
        $tables = RestaurantTable::all();

        return view(
            'admin.reservations.edit',
            compact('reservation', 'tables')
        );
    }

    public function update(
        UpdateReservationRequest $request,
        Reservation $reservation,
        ReservationService $reservationService
    ) {
        try {
            $reservationService->update(
                $reservation,
                $request->validated()
            );

            return redirect()
                ->route('admin.reservations.index')
                ->with('success', 'Reservation updated successfully');
        } catch (Exception $exception) {
            return back()
                ->withErrors([
                    'reservation_date' => $exception->getMessage(),
                ])
                ->withInput();
        }
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Reservation deleted successfully');
    }

    public function confirm(
        Reservation $reservation,
        ReservationService $reservationService
    ) {
        $reservationService->confirm($reservation);

        return back()
            ->with('success', 'Reservation confirmed successfully');
    }

    public function cancel(
        Reservation $reservation,
        ReservationService $reservationService
    ) {
        $reservationService->cancel($reservation);

        return back()
            ->with('success', 'Reservation cancelled successfully');
    }

    public function arrived(
        Reservation $reservation,
        ReservationService $reservationService
    ) {
        $reservationService->markArrived($reservation);

        return back()
            ->with('success', 'Reservation marked as arrived successfully');
    }

    public function noShow(
        Reservation $reservation,
        ReservationService $reservationService
    ) {
        $reservationService->markNoShow($reservation);

        return back()
            ->with('success', 'Reservation marked as no-show successfully');
    }
}
