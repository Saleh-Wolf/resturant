@extends('layouts.admin')

@section('title', 'Orders Report')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Orders Report</h1>

    <form method="GET"
          action="{{ route('admin.reports.orders') }}"
          class="card card-body mb-4">

        <div class="row">
            <div class="col-md-4">
                <label>Status</label>

                <select name="status" class="form-control">
                    <option value="">All Statuses</option>

                    @foreach(['pending', 'preparing', 'ready', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}"
                            {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary mr-2">
                    Filter
                </button>

                <a href="{{ route('admin.reports.orders') }}"
                   class="btn btn-secondary">
                    Reset
                </a>
            </div>
        </div>

    </form>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Table</th>
                        <th>Waiter</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Created At</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->table->table_number }}</td>
                            <td>{{ $order->waiter->name }}</td>

                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($order->status === 'preparing')
                                    <span class="badge badge-info">Preparing</span>
                                @elseif($order->status === 'ready')
                                    <span class="badge badge-primary">Ready</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge badge-danger">Cancelled</span>
                                @endif
                            </td>

                            <td>{{ number_format($order->total, 2) }} EGP</td>

                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $orders->links() }}

        </div>
    </div>

</div>
@endsection