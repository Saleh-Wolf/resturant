@extends('layouts.admin')

@section('title', 'Table Utilization Report')

@section('content')
    <div class="container-fluid">

        <h1 class="mb-4">
            Table Utilization Report
        </h1>
        <form method="GET" action="{{ route('admin.reports.table-utilization') }}" class="card card-body mb-4">

            <div class="row">

                <div class="col-md-3">
                    <label>From Date</label>

                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>

                <div class="col-md-3">
                    <label>To Date</label>

                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>

                <div class="col-md-2">
                    <label>Table Type</label>

                    <select name="type" class="form-control">

                        <option value="">All</option>

                        @foreach (['private', 'public', 'indoor', 'outdoor'] as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-2">
                    <label>Sort By</label>

                    <select name="sort" class="form-control">

                        <option value="">Table Number</option>

                        <option value="orders" {{ request('sort') === 'orders' ? 'selected' : '' }}>
                            Orders Count
                        </option>

                        <option value="revenue" {{ request('sort') === 'revenue' ? 'selected' : '' }}>
                            Revenue
                        </option>

                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary btn-block">
                        Filter
                    </button>
                </div>

            </div>

            <div class="mt-3">
                <a href="{{ route('admin.reports.table-utilization') }}" class="btn btn-secondary">
                    Reset
                </a>
            </div>

        </form>
        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Table</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Orders Count</th>
                            <th>Reservations Count</th>
                            <th>Revenue Generated</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tables as $table)
                            <tr>
                                <td>{{ $table->table_number }}</td>
                                <td>{{ ucfirst($table->type) }}</td>
                                <td>{{ $table->min_capacity }} - {{ $table->max_capacity }}</td>

                                <td>
                                    @if ($table->status === 'available')
                                        <span class="badge badge-success">Available</span>
                                    @elseif($table->status === 'occupied')
                                        <span class="badge badge-danger">Occupied</span>
                                    @elseif($table->status === 'reserved')
                                        <span class="badge badge-warning">Reserved</span>
                                    @else
                                        <span class="badge badge-secondary">
                                            {{ ucfirst($table->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td>{{ $table->orders_count }}</td>
                                <td>{{ $table->reservations_count }}</td>

                                <td>
                                    {{ number_format($table->revenue_generated ?? 0, 2) }} EGP
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    No tables found.
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
