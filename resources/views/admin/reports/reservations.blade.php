@extends('layouts.admin')

@section('title', 'Reservations Report')

@section('content')
    <div class="container-fluid">

        <h1 class="mb-4">Reservations Report</h1>

        <div class="row mb-4">

            <div class="col-md-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $totalReservations }}</h3>
                        <p>Total Reservations</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $confirmedReservations }}</h3>
                        <p>Confirmed</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $completedReservations }}</h3>
                        <p>Completed</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $cancelledReservations }}</h3>
                        <p>Cancelled / No Show</p>
                    </div>
                </div>
            </div>

        </div>

        <form method="GET" action="{{ route('admin.reports.reservations') }}" class="card card-body mb-4">

            <div class="row">
                <div class="col-md-4">
                    <label>Status</label>

                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>

                        @foreach (['pending', 'confirmed', 'arrived', 'completed', 'cancelled', 'no_show'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        Filter
                    </button>

                    <a href="{{ route('admin.reports.reservations') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </div>

        </form>

        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Reservation #</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Table</th>
                            <th>Type</th>
                            <th>Guests</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($reservations as $reservation)
                            <tr>

                                <td>
                                    {{ $reservation->reservation_number ?? '-' }}
                                </td>

                                <td>
                                    {{ $reservation->customer_name }}
                                </td>

                                <td>
                                    {{ $reservation->customer_phone }}
                                </td>

                                <td>
                                    {{ $reservation->table->table_number ?? '-' }}
                                </td>

                                <td>
                                    {{ ucfirst($reservation->reservation_type ?? '-') }}
                                </td>

                                <td>
                                    {{ $reservation->guest_count }}
                                </td>

                                <td>
                                    <span class="badge badge-secondary">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('Y-m-d H:i') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center">
                                    No reservations found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

                {{ $reservations->links() }}

            </div>
        </div>

    </div>
@endsection
