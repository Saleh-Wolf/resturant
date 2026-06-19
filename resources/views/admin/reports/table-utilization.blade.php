@extends('layouts.admin')

@section('title', 'Table Utilization Report')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">
        Table Utilization Report
    </h1>

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
                                @if($table->status === 'available')
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