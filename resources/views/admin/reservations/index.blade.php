@extends('layouts.admin')

@section('title', 'Reservations')

@section('content')

    <div class="container-fluid">

        <h1 class="mb-4">
            Reservations
        </h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">

                Create Reservation

            </a>
        </div>

        <form method="GET" action="{{ route('admin.reservations.index') }}" class="card card-body mb-4">

            <div class="row">

                <div class="col-md-3">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Name, phone, reservation no.">
                </div>

                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>

                        @foreach (['confirmed', 'arrived', 'completed', 'cancelled', 'no_show'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Type</label>
                    <select name="reservation_type" class="form-control">
                        <option value="">All Types</option>

                        @foreach (['immediate', 'scheduled'] as $type)
                            <option value="{{ $type }}"
                                {{ request('reservation_type') === $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-primary">
                    Filter
                </button>

                <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                    Reset
                </a>
            </div>

        </form>

        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>Reservation No.</th>
                            <th>Type</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Table</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Duration</th>
                            <th>Guests</th>
                            <th>Occasion</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($reservations as $reservation)
                            <tr>

                                <td>
                                    {{ $reservation->reservation_number ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge badge-secondary">
                                        {{ ucfirst($reservation->reservation_type ?? '-') }}
                                    </span>
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
                                    {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('Y-m-d') }}
                                </td>

                                <td>
                                    {{ $reservation->reservation_time ?? '-' }}
                                </td>

                                <td>
                                    @if ($reservation->estimated_duration)
                                        {{ $reservation->estimated_duration }} h
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ $reservation->guest_count }}
                                </td>

                                <td>
                                    {{ $reservation->special_occasion ?? '-' }}
                                </td>

                                <td>

                                    @if ($reservation->status === 'confirmed')
                                        <span class="badge badge-success">
                                            Confirmed
                                        </span>
                                    @elseif($reservation->status === 'arrived')
                                        <span class="badge badge-primary">
                                            Arrived
                                        </span>
                                    @elseif($reservation->status === 'completed')
                                        <span class="badge badge-info">
                                            Completed
                                        </span>
                                    @elseif($reservation->status === 'cancelled')
                                        <span class="badge badge-danger">
                                            Cancelled
                                        </span>
                                    @elseif($reservation->status === 'no_show')
                                        <span class="badge badge-dark">
                                            No Show
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="d-flex">

                                        @if ($reservation->status === 'confirmed')
                                            <form action="{{ route('admin.reservations.arrived', $reservation) }}"
                                                method="POST" class="mr-1">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-primary btn-sm">

                                                    Arrived

                                                </button>

                                            </form>

                                            <form action="{{ route('admin.reservations.no-show', $reservation) }}"
                                                method="POST" class="mr-1">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-dark btn-sm">

                                                    No Show

                                                </button>

                                            </form>

                                            <form action="{{ route('admin.reservations.cancel', $reservation) }}"
                                                method="POST" class="mr-1">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-danger btn-sm">

                                                    Cancel

                                                </button>

                                            </form>
                                        @endif

                                        @if ($reservation->status === 'arrived' && !$reservation->order)
                                            <a href="{{ route('waiter.orders.create', [
                                                'reservation_id' => $reservation->id,
                                            ]) }}"
                                                class="btn btn-success btn-sm mr-1">

                                                Create Order

                                            </a>
                                        @endif
                                        <a href="{{ route('admin.reservations.show', $reservation) }}"
                                            class="btn btn-info btn-sm mr-1">

                                            View

                                        </a>
                                        <a href="{{ route('admin.reservations.edit', $reservation) }}"
                                            class="btn btn-warning btn-sm mr-1">

                                            Edit

                                        </a>

                                        <form action="{{ route('admin.reservations.destroy', $reservation) }}"
                                            method="POST" onsubmit="return confirm('Delete this reservation?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm">

                                                Delete

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="12" class="text-center">

                                    No reservations found

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
