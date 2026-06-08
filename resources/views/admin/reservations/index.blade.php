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

    <div class="mb-3">
        <a href="{{ route('admin.reservations.create') }}"
           class="btn btn-primary">

            Create Reservation

        </a>
    </div>

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
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($reservations as $reservation)

                        <tr>

                            <td>
                                {{ $reservation->customer_name }}
                            </td>

                            <td>
                                {{ $reservation->customer_phone }}
                            </td>

                            <td>
                                {{ $reservation->table->table_number }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('Y-m-d H:i') }}
                            </td>

                            <td>
                                {{ $reservation->guest_count }}
                            </td>

                            <td>

                                @if($reservation->status === 'pending')

                                    <span class="badge badge-warning">
                                        Pending
                                    </span>

                                @elseif($reservation->status === 'confirmed')

                                    <span class="badge badge-success">
                                        Confirmed
                                    </span>

                                @elseif($reservation->status === 'completed')

                                    <span class="badge badge-info">
                                        Completed
                                    </span>

                                @elseif($reservation->status === 'cancelled')

                                    <span class="badge badge-danger">
                                        Cancelled
                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="d-flex">

                                    @if($reservation->status === 'pending')

                                        <form action="{{ route('admin.reservations.confirm', $reservation) }}"
                                              method="POST"
                                              class="mr-1">

                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-success btn-sm">

                                                Confirm

                                            </button>

                                        </form>

                                        <form action="{{ route('admin.reservations.cancel', $reservation) }}"
                                              method="POST"
                                              class="mr-1">

                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm">

                                                Cancel

                                            </button>

                                        </form>

                                    @endif

                                    <a href="{{ route('admin.reservations.edit', $reservation) }}"
                                       class="btn btn-warning btn-sm mr-1">

                                        Edit

                                    </a>

                                    <form action="{{ route('admin.reservations.destroy', $reservation) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this reservation?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm">

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7"
                                class="text-center">

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