@extends('layouts.admin')

@section('title', 'Tables')

@section('content')
    <div class="container-fluid">

        <h1 class="mb-4">Tables</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.tables.create') }}" class="btn btn-primary">
                Create Table
            </a>
        </div>

        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>Table Number</th>
                            <th>Type</th>
                            <th>Min Capacity</th>
                            <th>Max Capacity</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>QR Token</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tables as $table)
                            <tr>
                                <td>{{ $table->table_number }}</td>

                                <td>
                                    <span class="badge badge-secondary">
                                        {{ ucfirst($table->type) }}
                                    </span>
                                </td>

                                <td>{{ $table->min_capacity }}</td>

                                <td>{{ $table->max_capacity }}</td>

                                <td>{{ $table->location ?? '-' }}</td>

                                <td>
                                    @if ($table->status === 'available')
                                        <span class="badge badge-success">Available</span>
                                    @elseif($table->status === 'occupied')
                                        <span class="badge badge-danger">Occupied</span>
                                    @else
                                        <span class="badge badge-warning">Reserved</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($table->qr_token)
                                        <span class="badge badge-info">Generated</span>
                                    @else
                                        <span class="badge badge-danger">Missing</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <a href="{{ route('admin.tables.edit', $table) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.tables.destroy', $table) }}" method="POST"
                                            onsubmit="return confirm('Delete table?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                        <a href="{{ route('scan.menu', $table->qr_token) }}" target="_blank"
                                            class="btn btn-sm btn-info mr-1">
                                            QR Menu
                                        </a>

                                        <a href="{{ route('admin.tables.qr', $table) }}" target="_blank"
                                            class="btn btn-sm btn-primary mr-1">
                                            QR Code
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    No tables found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                {{ $tables->links() }}

            </div>
        </div>

    </div>
@endsection
