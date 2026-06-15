@extends('layouts.admin')

@section('title', 'Cashier Dashboard')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Cashier Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $pendingBillsCount }}</h3>
                    <p>Pending Bills</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($todayRevenue, 2) }}</h3>
                    <p>Today's Revenue</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Orders Ready For Billing</h3>
        </div>

        <div class="card-body table-responsive">

            @if($readyOrders->count())

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Table</th>
                            <th>Waiter</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($readyOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>

                                <td>
                                    @if($order->order_type === 'takeaway')
    <span class="badge badge-info">Takeaway</span>
@else
    {{ $order->table->table_number ?? '-' }}
@endif
                                </td>

                                <td>
                                    {{ $order->waiter->name ?? '-' }}
                                </td>

                                <td>
                                    <ul class="mb-0">
                                        @foreach($order->items as $item)
                                            <li>
                                                {{ $item->menuItem->name }}
                                                × {{ $item->quantity }}

                                                @if($item->offer)
                                                    <span class="badge badge-success">
                                                        {{ $item->offer->name }}
                                                    </span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <td>
                                    {{ number_format($order->total, 2) }} EGP
                                </td>

                                <td>
                                    <a href="#"
                                       class="btn btn-primary btn-sm">
                                        Generate Bill
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @else

                <p class="text-muted mb-0">
                    No orders ready for billing yet.
                </p>

            @endif

        </div>
    </div>

</div>
@endsection