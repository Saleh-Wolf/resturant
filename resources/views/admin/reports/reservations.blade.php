@extends('layouts.admin')

@section('title', 'Reservations Report')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Reservations Report</h1>

    <form method="GET"
          action="{{ route('admin.reports.reservations') }}"
          class="card card-body mb-4">

        <div class="row">
            <div class="col-md-4">
                <label>Status</label>

                <select name="status" class="form-control">
                    <option value="">All Statuses</option>

                    @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}"
                            {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary mr-2">Filter</button>

                <a href="{{ route('admin.reports.reservations') }}"
                   class="btn btn-secondary">
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
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Table</th>
                        <th>Date & Time</th>
                        <th>Guests</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->customer_name }}</td>
                            <td>{{ $reservation->customer_phone }}</td>
                            <td>{{ $reservation->table->table_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('Y-m-d H:i') }}</td>
                            <td>{{ $reservation->guest_count }}</td>

                            <td>
                                @if($reservation->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($reservation->status === 'confirmed')
                                    <span class="badge badge-success">Confirmed</span>
                                @elseif($reservation->status === 'completed')
                                    <span class="badge badge-info">Completed</span>
                                @elseif($reservation->status === 'cancelled')
                                    <span class="badge badge-danger">Cancelled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
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