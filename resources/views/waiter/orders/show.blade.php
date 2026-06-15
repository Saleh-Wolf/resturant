@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')

    <div class="container-fluid">

        <h1 class="mb-4">

            Order #{{ $order->id }}

        </h1>

        <div class="card mb-4">

            <div class="card-body">

                <p>
                    <strong>Order Type:</strong>

                    @if ($order->order_type === 'takeaway')
                        <span class="badge badge-info">Takeaway</span>
                    @else
                        <span class="badge badge-success">Dine In</span>
                    @endif
                </p>

                <p>
                    @if ($order->order_type === 'takeaway')
                        <span class="badge badge-info">
                            Takeaway
                        </span>
                    @else
                        {{ $order->table->table_number ?? '-' }}
                    @endif
                </p>

                <p>
                    <strong>Waiter:</strong>
                    {{ $order->waiter->name }}
                </p>

                <p>
                    <strong>Status:</strong>
                    {{ ucfirst($order->status) }}
                </p>

            </div>

        </div>

        <div class="card">

            <div class="card-header">

                Order Items

            </div>

            <div class="card-body table-responsive">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Original Price</th>
                            <th>Discount</th>
                            <th>Final Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($order->items as $item)
                            <tr>

                                <td>

                                    {{ $item->menuItem->name }}

                                    @if ($item->offer_id)
                                        <span class="badge badge-success">

                                            OFFER

                                        </span>
                                    @endif

                                </td>

                                <td>
                                    {{ $item->quantity }}
                                </td>

                                <td>
                                    {{ number_format($item->original_unit_price, 2) }}
                                </td>

                                <td>
                                    {{ number_format($item->discount_amount, 2) }}
                                </td>

                                <td>
                                    {{ number_format($item->unit_price, 2) }}
                                </td>

                                <td>
                                    {{ number_format($item->total_price, 2) }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card mt-3">

            <div class="card-body text-right">

                <h4>

                    Total:
                    {{ number_format($order->total, 2) }}

                </h4>

            </div>

        </div>

    </div>

@endsection
