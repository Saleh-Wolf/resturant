@extends('layouts.admin')

@section('title', 'Kitchen Orders')

@section('content')

    <meta http-equiv="refresh" content="15">

    <div class="container-fluid">

        <h1 class="mb-4">Kitchen Orders</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $pendingOrders->count() }}</h3>
                        <p>Pending Orders</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $preparingOrders->count() }}</h3>
                        <p>Preparing Orders</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $readyOrders->count() }}</h3>
                        <p>Ready Orders</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            @foreach ([
            'Pending Orders' => [$pendingOrders, 'danger', 'kitchen.orders.preparing', 'Start Preparing'],
            'In Preparation' => [$preparingOrders, 'warning', 'kitchen.orders.ready', 'Mark Ready'],
            'Ready Orders' => [$readyOrders, 'success', null, null],
        ] as $title => [$orders, $color, $route, $button])
                <div class="col-md-4">
                    <div class="card border-{{ $color }}">
                        <div class="card-header bg-{{ $color }} {{ $color === 'warning' ? '' : 'text-white' }}">
                            <h3 class="card-title mb-0">{{ $title }}</h3>
                        </div>

                        <div class="card-body">
                            @forelse($orders as $order)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h4>Order #{{ $order->id }}</h4>

                                        <p>
                                            @if ($order->order_type === 'takeaway')
                                                <span class="badge badge-info">Takeaway</span>
                                            @else
                                                {{ $order->table->table_number ?? '-' }}
                                            @endif
                                        </p>

                                        <p>
                                            <strong>Created:</strong>
                                            {{ $order->created_at->diffForHumans() }}
                                        </p>

                                        <ul>
                                            @foreach ($order->items as $item)
                                                <li>
                                                    <strong>{{ $item->menuItem->name }}</strong>
                                                    × {{ $item->quantity }}

                                                    @if ($item->notes)
                                                        <div class="text-danger">
                                                            Note: {{ $item->notes }}
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>

                                        @if ($route && $button)
                                            <form action="{{ route($route, $order) }}" method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <button class="btn btn-primary btn-block">
                                                    {{ $button }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge badge-success">
                                                Ready for Cashier
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">
                                    No orders.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

    </div>

@endsection
