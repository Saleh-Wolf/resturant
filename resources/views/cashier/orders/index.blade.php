@extends('layouts.admin')

@section('title', 'Cashier Orders')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Cashier Orders</h1>
        <div class="row mb-4">

    <div class="col-md-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($todaySales, 2) }} EGP</h3>
                <p>Today's Sales</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $completedOrdersToday }}</h3>
                <p>Completed Orders Today</p>
            </div>
            <div class="icon">
                <i class="fas fa-receipt"></i>
            </div>
        </div>
    </div>

</div>


    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Table</th>
                        <th>Waiter</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->table->table_number }}</td>
                            <td>{{ $order->waiter->name }}</td>

                            <td>
                                <ul class="mb-0">
                                    @foreach($order->items as $item)
                                        <li>
                                            {{ $item->menuItem->name }}
                                            × {{ $item->quantity }}
                                            = {{ number_format($item->total_price, 2) }} EGP
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>{{ number_format($order->total, 2) }} EGP</td>

                            <td>
                                <form action="{{ route('cashier.orders.complete', $order) }}"
                                      method="POST"
                                      onsubmit="return confirm('Complete this order?')">
                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-success btn-sm">
                                        Complete Order
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                No ready orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection