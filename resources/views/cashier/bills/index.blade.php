@extends('layouts.admin')

@section('title', 'Bills')

@section('content')
    <div class="container-fluid">

        <h1 class="mb-4">Bills</h1>

        <form method="GET" action="{{ route('cashier.bills.index') }}" class="card card-body mb-4">

            <div class="row">

                <div class="col-md-3">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Bill #, Order #, Customer">
                </div>

                <div class="col-md-2">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>

                <div class="col-md-2">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>

                <div class="col-md-2">
                    <label>Payment</label>
                    <select name="payment_method" class="form-control">
                        <option value="">All</option>
                        @foreach (['cash', 'card', 'upi', 'other'] as $method)
                            <option value="{{ $method }}"
                                {{ request('payment_method') === $method ? 'selected' : '' }}>
                                {{ ucfirst($method) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Status</label>
                    <select name="payment_status" class="form-control">
                        <option value="">All</option>
                        @foreach (['pending', 'paid', 'partially_paid', 'cancelled'] as $status)
                            <option value="{{ $status }}"
                                {{ request('payment_status') === $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-primary btn-block">
                        Filter
                    </button>
                </div>

            </div>

            <div class="mt-3">
                <a href="{{ route('cashier.bills.index') }}" class="btn btn-secondary">
                    Reset
                </a>
            </div>

        </form>

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

                                    @if ($bill->payment_status === 'paid')
                                        <span class="badge badge-success">
                                            Paid
                                        </span>
                                    @elseif($bill->payment_status === 'pending')
                                        <span class="badge badge-warning">
                                            Pending
                                        </span>
                                    @elseif($bill->payment_status === 'partially_paid')
                                        <span class="badge badge-info">
                                            Partially Paid
                                        </span>
                                    @elseif($bill->payment_status === 'cancelled')
                                        <span class="badge badge-danger">
                                            Cancelled
                                        </span>
                                    @endif

                                </td>
                                <td>{{ $bill->paid_at }}</td>
                                <td>

                                    <a href="{{ route('cashier.bills.show', $bill) }}" class="btn btn-primary btn-sm">
                                        View
                                    </a>

                                    @if ($bill->payment_status !== 'cancelled')
                                        <form action="{{ route('cashier.bills.void', $bill) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Cancel this bill?')">

                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Void
                                            </button>

                                        </form>
                                    @endif

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
