@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Orders</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('waiter.orders.create') }}"
       class="btn btn-primary mb-3">
        Create Order
    </a>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Type</th>
                        <th>Table</th>
                        <th>Waiter</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>

                            <td>
                                @if($order->order_type === 'takeaway')
                                    <span class="badge badge-info">
                                        Takeaway
                                    </span>
                                @else
                                    <span class="badge badge-success">
                                        Dine In
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $order->table->table_number ?? '-' }}
                            </td>

                            <td>
                                {{ $order->waiter->name ?? '-' }}
                            </td>

                            <td>
                                <span class="badge badge-warning">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <td>
                                {{ number_format($order->total, 2) }} EGP
                            </td>

                            <td>
                                {{ $order->created_at->format('Y-m-d H:i') }}
                            </td>

                            <td>
                                <a href="{{ route('waiter.orders.show', $order) }}"
                                   class="btn btn-sm btn-info">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
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