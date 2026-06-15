@extends('layouts.admin')

@section('title', 'Bills')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Bills</h1>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Bill #</th>
                        <th>Order #</th>
                        <th>Table</th>
                        <th>Cashier</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Paid At</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bills as $bill)
                        <tr>
                            <td>{{ $bill->bill_number }}</td>
                            <td>#{{ $bill->order_id }}</td>
                            <td>{{ $bill->order->table->table_number ?? '-' }}</td>
                            <td>{{ $bill->cashier->name ?? '-' }}</td>
                            <td>{{ number_format($bill->grand_total, 2) }} EGP</td>
                            <td>{{ ucfirst($bill->payment_method) }}</td>
                            <td>
                                <span class="badge badge-success">
                                    {{ ucfirst($bill->payment_status) }}
                                </span>
                            </td>
                            <td>{{ $bill->paid_at }}</td>
                            <td>
                                <a href="{{ route('cashier.bills.show', $bill) }}"
                                   class="btn btn-primary btn-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                No bills found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $bills->links() }}

        </div>
    </div>

</div>
@endsection