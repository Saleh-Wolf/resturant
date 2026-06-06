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
}