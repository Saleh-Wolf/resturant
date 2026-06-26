@extends('layouts.admin')

@section('title', 'Table Utilization Report')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">
        Table Utilization Report
    </h1>

    <form method="GET"
          action="{{ route('admin.reports.table-utilization') }}"
          class="card card-body mb-4">

        <div class="row">

            <div class="col-md-3">
                <label>From Date</label>
                <input type="date"
                       name="from_date"
                       class="form-control"
                       value="{{ request('from_date') }}">
            </div>

            <div class="col-md-3">
                <label>To Date</label>
                <input type="date"
                       name="to_date"
                       class="form-control"
                       value="{{ request('to_date') }}">
            </div>

            <div class="col-md-2">
                <label>Table Type</label>

                <select name="type" class="form-control">
                    <option value="">All</option>

                    @foreach(['private', 'public', 'indoor', 'outdoor'] as $type)
                        <option value="{{ $type }}"
                            {{ request('type') === $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>Sort By</label>

                <select name="sort" class="form-control">
                    <option value="">Table Number</option>

                    <option value="orders"
                        {{ request('sort') === 'orders' ? 'selected' : '' }}>
                        Orders Count
                    </option>

                    <option value="revenue"
                        {{ request('sort') === 'revenue' ? 'selected' : '' }}>
                        Revenue
                    </option>

                    <option value="utilization"
                        {{ request('sort') === 'utilization' ? 'selected' : '' }}>
                        Utilization
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <label>&nbsp;</label>

                <div>
                    <button class="btn btn-primary">
                        Filter
                    </button>

                    <a href="{{ route('admin.reports.table-utilization') }}"
                       class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </div>

        </div>

    </form>



<div class="row mb-4">

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success">
                <strong>Most Used Tables</strong>
            </div>

            <div class="card-body">

                <ol class="mb-0">
                    @forelse($mostUsedTables as $table)
                        <li>
                            Table {{ $table->table_number }}
                            <span class="float-right">
                                {{ $table->orders_count_filtered }} Orders
                            </span>
                        </li>
                    @empty
                        <li>No data</li>
                    @endforelse
                </ol>

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning">
                <strong>Least Used Tables</strong>
            </div>

            <div class="card-body">

                <ol class="mb-0">
                    @forelse($leastUsedTables as $table)
                        <li>
                            Table {{ $table->table_number }}
                            <span class="float-right">
                                {{ $table->orders_count_filtered }} Orders
                            </span>
                        </li>
                    @empty
                        <li>No data</li>
                    @endforelse
                </ol>

            </div>
        </div>
    </div>

</div>






    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Table</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Times Used</th>
                        <th>Revenue Generated</th>
                        <th>Total Hours</th>
                        <th>Avg Duration</th>
                        <th>Utilization</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tables as $table)
                        <tr>
                            <td>{{ $table->table_number }}</td>

                            <td>{{ ucfirst($table->type) }}</td>

                            <td>
                                {{ $table->min_capacity }}
                                -
                                {{ $table->max_capacity }}
                            </td>

                            <td>
                                @if($table->status === 'available')
                                    <span class="badge badge-success">
                                        Available
                                    </span>
                                @elseif($table->status === 'occupied')
                                    <span class="badge badge-danger">
                                        Occupied
                                    </span>
                                @elseif($table->status === 'reserved')
                                    <span class="badge badge-warning">
                                        Reserved
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        {{ ucfirst($table->status) }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $table->orders_count_filtered ?? 0 }}
                            </td>

                            <td>
                                {{ number_format($table->revenue_generated_filtered ?? 0, 2) }} EGP
                            </td>

                            <td>
                                {{ number_format($table->total_hours_occupied ?? 0, 2) }} h
                            </td>

                            <td>
                                {{ number_format($table->average_duration_minutes ?? 0, 0) }} min
                            </td>

                            <td>
                                {{ number_format($table->utilization_rate ?? 0, 1) }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                No tables found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection