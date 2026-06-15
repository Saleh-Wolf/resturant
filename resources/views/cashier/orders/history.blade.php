@extends('layouts.admin')

@section('title', 'Order History')

@section('content')

    <div class="container-fluid">

        <h1 class="mb-4">
            Order History
        </h1>

        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Table</th>
                            <th>Waiter</th>
                            <th>Total</th>
                            <th>Completed At</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($orders as $order)
                            <tr>

                                <td>
                                    #{{ $order->id }}
                                </td>

                                <td>
                                    @if ($order->order_type === 'takeaway')
                                        <span class="badge badge-info">Takeaway</span>
                                    @else
                                        {{ $order->table->table_number ?? '-' }}
                                    @endif
                                </td>

                                <td>
                                    {{ $order->waiter->name }}
                                </td>

                                <td>
                                    {{ number_format($order->total, 2) }} EGP
                                </td>

                                <td>
                                    {{ $order->updated_at->format('Y-m-d H:i') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center">

                                    No completed orders found

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
