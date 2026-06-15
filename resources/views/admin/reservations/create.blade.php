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
                    <label>Reservation Type</label>

                    <select name="reservation_type"
                            class="form-control"
                            required>
                        <option value="immediate" {{ old('reservation_type') === 'immediate' ? 'selected' : '' }}>
                            Immediate
                        </option>

                        <option value="scheduled" {{ old('reservation_type') === 'scheduled' ? 'selected' : '' }}>
                            Scheduled
                        </option>
                    </select>
                </div>

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
                            <option value="{{ $table->id }}"
                                {{ old('restaurant_table_id') == $table->id ? 'selected' : '' }}>

                                {{ $table->table_number }}
                                -
                                {{ ucfirst($table->type) }}
                                -
                                Capacity:
                                {{ $table->min_capacity }}
                                -
                                {{ $table->max_capacity }}

                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label>Reservation Date</label>

                    <input type="date"
                           name="reservation_date"
                           class="form-control"
                           value="{{ old('reservation_date') }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Reservation Time</label>

                    <input type="time"
                           name="reservation_time"
                           class="form-control"
                           value="{{ old('reservation_time') }}">
                </div>

                <div class="form-group">
                    <label>Estimated Duration</label>

                    <select name="estimated_duration"
                            class="form-control">

                        <option value="">
                            Select Duration
                        </option>

                        <option value="1" {{ old('estimated_duration') == '1' ? 'selected' : '' }}>
                            1 Hour
                        </option>

                        <option value="1.5" {{ old('estimated_duration') == '1.5' ? 'selected' : '' }}>
                            1.5 Hours
                        </option>

                        <option value="2" {{ old('estimated_duration') == '2' ? 'selected' : '' }}>
                            2 Hours
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label>Guest Count</label>

                    <input type="number"
                           name="guest_count"
                           class="form-control"
                           value="{{ old('guest_count') }}"
                           min="1"
                           required>
                </div>

                <div class="form-group">
                    <label>Special Occasion</label>

                    <select name="special_occasion"
                            class="form-control">

                        <option value="">
                            None
                        </option>

                        <option value="Birthday" {{ old('special_occasion') === 'Birthday' ? 'selected' : '' }}>
                            Birthday
                        </option>

                        <option value="Anniversary" {{ old('special_occasion') === 'Anniversary' ? 'selected' : '' }}>
                            Anniversary
                        </option>

                        <option value="Business" {{ old('special_occasion') === 'Business' ? 'selected' : '' }}>
                            Business
                        </option>

                        <option value="Other" {{ old('special_occasion') === 'Other' ? 'selected' : '' }}>
                            Other
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label>Notes</label>

                    <textarea name="notes"
                              class="form-control"
                              rows="3">{{ old('notes') }}</textarea>
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