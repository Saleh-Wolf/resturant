@extends('layouts.admin')

@section('title', 'Kitchen Orders')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4">
        Kitchen Orders
    </h1>

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
                        <th>Items</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($orders as $order)

                        <tr>

                            <td>
                                #{{ $order->id }}
                            </td>

                            <td>
                                {{ $order->table->table_number }}
                            </td>

                            <td>

                                <ul class="mb-0">

                                    @foreach($order->items as $item)

                                        <li>
                                            {{ $item->menuItem->name }}
                                            ×
                                            {{ $item->quantity }}
                                        </li>

                                    @endforeach

                                </ul>

                            </td>

                            <td>

                                @if($order->status === 'pending')
                                    <span class="badge badge-warning">
                                        Pending
                                    </span>

                                @elseif($order->status === 'preparing')
                                    <span class="badge badge-info">
                                        Preparing
                                    </span>

                                @elseif($order->status === 'ready')
                                    <span class="badge badge-success">
                                        Ready
                                    </span>
                                @endif

                            </td>

                            <td>

                                @if($order->status === 'pending')

                                    <form action="{{ route('kitchen.orders.preparing', $order) }}"
                                          method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-primary btn-sm">

                                            Start Preparing

                                        </button>

                                    </form>

                                @elseif($order->status === 'preparing')

                                    <form action="{{ route('kitchen.orders.ready', $order) }}"
                                          method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-success btn-sm">

                                            Mark Ready

                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center">

                                No orders found

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection