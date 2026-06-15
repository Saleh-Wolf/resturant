@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="mb-4">Admin Dashboard</h1>
        </div>
    </div>

    <div class="container-fluid">

        @include('admin.partials.stats-cards')

        <div class="row mt-4">

            <div class="col-md-8">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">
                            Recent Orders
                        </h3>
                    </div>

                    <div class="card-body table-responsive">

                        <table class="table table-bordered table-striped">

                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Table</th>
                                    <th>Waiter</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($recentOrders as $order)
                                    <tr>

                                        <td>
                                            #{{ $order->id }}
                                        </td>

                                        <td>
                                            {{ $order->table->table_number ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $order->waiter->name ?? '-' }}
                                        </td>

                                        <td>

                                            @if ($order->status === 'pending')
                                                <span class="badge badge-warning">
                                                    Pending
                                                </span>
                                            @elseif($order->status === 'preparing')
                                                <span class="badge badge-info">
                                                    Preparing
                                                </span>
                                            @elseif($order->status === 'ready')
                                                <span class="badge badge-primary">
                                                    Ready
                                                </span>
                                            @elseif($order->status === 'completed')
                                                <span class="badge badge-success">
                                                    Completed
                                                </span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="badge badge-danger">
                                                    Cancelled
                                                </span>
                                            @endif

                                        </td>

                                        <td>
                                            {{ number_format($order->total, 2) }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5" class="text-center">
                                            No recent orders found.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">
                            Quick Links
                        </h3>
                    </div>

                    <div class="card-body">

                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-block mb-2">
                            Manage Users
                        </a>

                        <a href="{{ route('admin.menu-items.index') }}" class="btn btn-success btn-block mb-2">
                            Manage Menu
                        </a>

                        <a href="{{ route('admin.tables.index') }}" class="btn btn-warning btn-block mb-2">
                            Manage Tables
                        </a>

                        <a href="{{ route('admin.reports.sales') }}" class="btn btn-info btn-block">
                            View Reports
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
