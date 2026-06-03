@extends('layouts.admin')

@section('title', 'Create Table')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-3">
            <h1>Create Table</h1>

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

                <form action="{{ route('admin.tables.store') }}" method="POST">

                    @csrf

                    <div class="form-group">
                        <label>Table Number</label>

                        <input type="text" name="table_number" class="form-control" value="{{ old('table_number') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Table Type</label>

                        <select name="type" class="form-control">
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Minimum Capacity</label>

                        <input type="number" name="min_capacity" class="form-control" min="1" value="1"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Maximum Capacity</label>

                        <input type="number" name="max_capacity" class="form-control" min="1" value="1"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Location</label>

                        <input type="text" name="location" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Status</label>

                        <select name="status" class="form-control" required>

                            <option value="available">
                                Available
                            </option>

                            <option value="occupied">
                                Occupied
                            </option>

                            <option value="reserved">
                                Reserved
                            </option>

                        </select>
                    </div>

                    <div class="form-group">
                        <label>Notes</label>

                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">

                        Create Table

                    </button>

                </form>

            </div>
        </div>

    </div>
@endsection
