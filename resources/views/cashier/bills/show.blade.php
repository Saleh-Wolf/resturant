@extends('layouts.admin')

@section('title', 'Bill Details')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h1>Bill Details</h1>

            <button onclick="window.print()" class="btn btn-secondary">
                Print Receipt
            </button>
        </div>

        <div class="card">
            <div class="card-body">

                <h4 class="text-center">Sofra</h4>
                <p class="text-center mb-4">Receipt</p>

                <p><strong>Bill #:</strong> {{ $bill->bill_number }}</p>
                <p><strong>Order #:</strong> #{{ $bill->order_id }}</p>
                <p><strong>Table:</strong>
                    @if ($bill->order->order_type === 'takeaway')
                        <span class="badge badge-info">Takeaway</span>
                    @else
                        {{ $bill->order->table->table_number ?? '-' }}
                    @endif
                </p>
                <p><strong>Waiter:</strong> {{ $bill->order->waiter->name ?? '-' }}</p>
                <p><strong>Cashier:</strong> {{ $bill->cashier->name ?? '-' }}</p>
                <p><strong>Paid At:</strong> {{ $bill->paid_at }}</p>

                <hr>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Original</th>
                            <th>Discount</th>
                            <th>Final</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($bill->order->items as $item)
                            <tr>
                                <td>
                                    {{ $item->menuItem->name }}

                                    @if ($item->offer)
                                        <br>
                                        <small class="text-success">
                                            Offer: {{ $item->offer->name }}
                                        </small>
                                    @endif
                                </td>

                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->original_unit_price, 2) }}</td>
                                <td>{{ number_format($item->discount_amount, 2) }}</td>
                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>

                <div class="text-right">
                    <p><strong>Subtotal:</strong> {{ number_format($bill->subtotal, 2) }} EGP</p>
                    <p><strong>Discount:</strong> {{ number_format($bill->discount_total, 2) }} EGP</p>
                    <p><strong>Tax:</strong> {{ number_format($bill->tax_amount, 2) }} EGP</p>
                    <p><strong>Service:</strong> {{ number_format($bill->service_charge, 2) }} EGP</p>
                    <h4><strong>Grand Total:</strong> {{ number_format($bill->grand_total, 2) }} EGP</h4>
                    <p><strong>Payment Method:</strong> {{ ucfirst($bill->payment_method) }}</p>
                    <p><strong>Amount Received:</strong> {{ number_format($bill->amount_received, 2) }} EGP</p>
                    <p><strong>Change:</strong> {{ number_format($bill->change_amount, 2) }} EGP</p>
                </div>

            </div>
        </div>

    </div>
@endsection
