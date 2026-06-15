@extends('layouts.admin')

@section('title', 'Kitchen View')

@section('content')

<meta http-equiv="refresh" content="15">

<div class="container-fluid">

    <h1 class="mb-4">Kitchen View</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">

        <div class="col-md-6">
            <div class="card border-danger">

                <div class="card-header bg-danger text-white">
                    <h3 class="card-title mb-0">
                        Pending Orders
                    </h3>
                </div>

                <div class="card-body">

                    @forelse($pendingOrders as $order)

                        <div class="card mb-3">

                            <div class="card-body">

                                <h4>
                                    Order #{{ $order->id }}
                                </h4>

                                <p>
                                @if($order->order_type === 'takeaway')
    <span class="badge badge-info">Takeaway</span>
@else
    {{ $order->table->table_number ?? '-' }}
@endif
                                </p>

                                <p>
                                    <strong>Time:</strong>
                                    {{ $order->created_at->diffForHumans() }}
                                </p>

                                <ul>
                                    @foreach($order->items as $item)
                                        <li>
                                            <strong>
                                                {{ $item->menuItem->name }}
                                            </strong>

                                            ×

                                            {{ $item->quantity }}

                                            @if($item->notes)
                                                <div class="text-danger">
                                                    Note: {{ $item->notes }}
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

                                <form action="{{ route('kitchen.orders.preparing', $order) }}"
                                      method="POST">

                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-primary btn-block">
                                        Start Preparing
                                    </button>

                                </form>

                            </div>

                        </div>

                    @empty

                        <p class="text-muted">
                            No pending orders.
                        </p>

                    @endforelse

                </div>

            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-warning">

                <div class="card-header bg-warning">
                    <h3 class="card-title mb-0">
                        In Preparation
                    </h3>
                </div>

                <div class="card-body">

                    @forelse($preparingOrders as $order)

                        <div class="card mb-3">

                            <div class="card-body">

                                <h4>
                                    Order #{{ $order->id }}
                                </h4>

                                <p>
                                    @if($order->order_type === 'takeaway')
                                        <span class="badge badge-info">Takeaway</span>
                                    @else
                                        <strong>Table:</strong>
                                        {{ $order->table->table_number ?? '-' }}
                                    @endif
                                </p>

                                <p>
                                    <strong>Preparing Since:</strong>
                                    {{ $order->updated_at->diffForHumans() }}
                                </p>

                                <ul>
                                    @foreach($order->items as $item)
                                        <li>
                                            <strong>
                                                {{ $item->menuItem->name }}
                                            </strong>

                                            ×

                                            {{ $item->quantity }}

                                            @if($item->notes)
                                                <div class="text-danger">
                                                    Note: {{ $item->notes }}
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

                                <form action="{{ route('kitchen.orders.ready', $order) }}"
                                      method="POST">

                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-success btn-block">
                                        Mark Ready
                                    </button>

                                </form>

                            </div>

                        </div>

                    @empty

                        <p class="text-muted">
                            No orders in preparation.
                        </p>

                    @endforelse

                </div>

            </div>
        </div>

    </div>

</div>

@endsection