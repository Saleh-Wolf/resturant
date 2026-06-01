@extends('layouts.admin')

@section('title', 'Edit Table')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-3">
            <h1>Edit Table</h1>

            <a href="{{ route('admin.tables.index') }}" class="btn btn-secondary">
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

                <form action="{{ route('admin.tables.update', $restaurantTable) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Table Number</label>

                        <input type="text" name="table_number" class="form-control"
                            value="{{ old('table_number', $restaurantTable->table_number) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Capacity</label>

                        <input type="number" name="capacity" class="form-control" min="1"
                            value="{{ old('capacity', $restaurantTable->capacity) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>

                        <select name="status" class="form-control" required>

                            <option value="available" {{ $restaurantTable->status == 'available' ? 'selected' : '' }}>
                                Available
                            </option>

                            <option value="occupied" {{ $restaurantTable->status == 'occupied' ? 'selected' : '' }}>
                                Occupied
                            </option>

                            <option value="reserved" {{ $restaurantTable->status == 'reserved' ? 'selected' : '' }}>
                                Reserved
                            </option>

                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">

                        Update Table

                    </button>

                </form>

            </div>
        </div>

    </div>
@endsection
