@extends('layouts.admin')

@section('title', 'Create Reservation')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4">
        Create Reservation
    </h1>

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

            <form action="{{ route('admin.reservations.store') }}"
                  method="POST">

                @csrf

                <div class="form-group">
                    <label>Customer Name</label>

                    <input type="text"
                           name="customer_name"
                           class="form-control"
                           value="{{ old('customer_name') }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Customer Phone</label>

                    <input type="text"
                           name="customer_phone"
                           class="form-control"
                           value="{{ old('customer_phone') }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Table</label>

                    <select name="restaurant_table_id"
                            class="form-control"
                            required>

                        <option value="">
                            Select Table
                        </option>

                        @foreach($tables as $table)

                            <option value="{{ $table->id }}">

                                {{ $table->table_number }}
                                -
                                Capacity:
                                {{ $table->max_capacity }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="form-group">
                    <label>Reservation Date & Time</label>

                    <input type="datetime-local"
                           name="reservation_date"
                           class="form-control"
                           required>
                </div>

                <div class="form-group">
                    <label>Guest Count</label>

                    <input type="number"
                           name="guest_count"
                           class="form-control"
                           min="1"
                           required>
                </div>

                <div class="form-group">
                    <label>Notes</label>

                    <textarea name="notes"
                              class="form-control"
                              rows="3"></textarea>
                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Create Reservation

                </button>

            </form>

        </div>
    </div>

</div>

@endsection