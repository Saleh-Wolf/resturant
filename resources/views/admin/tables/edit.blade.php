@extends('layouts.admin')

@section('title', 'Edit Table')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h1>Edit Table</h1>

        <a href="{{ route('admin.tables.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>

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

            <form action="{{ route('admin.tables.update', $restaurantTable) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Table Number</label>

                    <input type="text"
                           name="table_number"
                           class="form-control"
                           value="{{ old('table_number', $restaurantTable->table_number) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Table Type</label>

                    <select name="type"
                            class="form-control"
                            required>

                        <option value="public"
                            {{ $restaurantTable->type == 'public' ? 'selected' : '' }}>
                            Public
                        </option>

                        <option value="private"
                            {{ $restaurantTable->type == 'private' ? 'selected' : '' }}>
                            Private
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label>Minimum Capacity</label>

                    <input type="number"
                           name="min_capacity"
                           class="form-control"
                           min="1"
                           value="{{ old('min_capacity', $restaurantTable->min_capacity) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Maximum Capacity</label>

                    <input type="number"
                           name="max_capacity"
                           class="form-control"
                           min="1"
                           value="{{ old('max_capacity', $restaurantTable->max_capacity) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Location</label>

                    <input type="text"
                           name="location"
                           class="form-control"
                           value="{{ old('location', $restaurantTable->location) }}">
                </div>

                <div class="form-group">
                    <label>Status</label>

                    <select name="status"
                            class="form-control"
                            required>

                        <option value="available"
                            {{ $restaurantTable->status == 'available' ? 'selected' : '' }}>
                            Available
                        </option>

                        <option value="occupied"
                            {{ $restaurantTable->status == 'occupied' ? 'selected' : '' }}>
                            Occupied
                        </option>

                        <option value="reserved"
                            {{ $restaurantTable->status == 'reserved' ? 'selected' : '' }}>
                            Reserved
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label>Notes</label>

                    <textarea name="notes"
                              class="form-control"
                              rows="3">{{ old('notes', $restaurantTable->notes) }}</textarea>
                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Update Table

                </button>

            </form>

        </div>
    </div>

</div>
@endsection
