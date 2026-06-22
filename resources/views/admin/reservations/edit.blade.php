@extends('layouts.admin')

@section('title', 'Edit Reservation')

@section('content')
    <div class="container-fluid">

        <h1 class="mb-4">Edit Reservation</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Customer Name</label>
                        <input type="text" name="customer_name" class="form-control"
                            value="{{ old('customer_name', $reservation->customer_name) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Customer Phone</label>
                        <input type="text" name="customer_phone" class="form-control"
                            value="{{ old('customer_phone', $reservation->customer_phone) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Table</label>
                        <select name="restaurant_table_id" class="form-control" required>
                            @foreach ($tables as $table)
                                <option value="{{ $table->id }}"
                                    {{ $reservation->restaurant_table_id == $table->id ? 'selected' : '' }}>
                                    {{ $table->table_number }} - Capacity: {{ $table->max_capacity }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Reservation Type</label>

                        <select name="reservation_type" class="form-control" required>
                            <option value="immediate"
                                {{ old('reservation_type', $reservation->reservation_type) === 'immediate' ? 'selected' : '' }}>
                                Immediate
                            </option>

                            <option value="scheduled"
                                {{ old('reservation_type', $reservation->reservation_type) === 'scheduled' ? 'selected' : '' }}>
                                Scheduled
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Reservation Date & Time</label>
                        <input type="datetime-local" name="reservation_date" class="form-control"
                            value="{{ old('reservation_date', \Carbon\Carbon::parse($reservation->reservation_date)->format('Y-m-d\TH:i')) }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Guest Count</label>
                        <input type="number" name="guest_count" class="form-control" min="1"
                            value="{{ old('guest_count', $reservation->guest_count) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}"
                                    {{ $reservation->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $reservation->notes) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Update Reservation
                    </button>

                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                        Back
                    </a>
                </form>

            </div>
        </div>

    </div>
@endsection
