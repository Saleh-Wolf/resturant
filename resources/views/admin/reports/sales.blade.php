@extends('layouts.admin')

@section('title', 'Sales Report')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Sales Report</h1>

    <form method="GET" action="{{ route('admin.reports.sales') }}" class="card card-body mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>From Date</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>

            <div class="col-md-4">
                <label>To Date</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary mr-2">Filter</button>

                <a href="{{ route('admin.reports.sales') }}" class="btn btn-secondary">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalSales, 2) }}</h3>
                    <p>Total Sales</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $completedOrdersCount }}</h3>
                    <p>Paid Bills</p>
                </div>
                <div class="icon">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($averageOrderValue, 2) }}</h3>
                    <p>Average Bill Value</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($totalDiscount, 2) }}</h3>
                    <p>Total Discount</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($cashSales, 2) }}</h3>
                    <p>Cash Sales</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ number_format($cardSales, 2) }}</h3>
                    <p>Card Sales</p>
                </div>
                <div class="icon">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Bill #</th>
                        <th>Order #</th>
                        <th>Table</th>
                        <th>Waiter</th>
                        <th>Cashier</th>
                        <th>Payment</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Paid At</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bills as $bill)
                        <tr>
                            <td>{{ $bill->bill_number }}</td>
                            <td>#{{ $bill->order_id }}</td>
                            <td>{{ $bill->order->table->table_number ?? '-' }}</td>
                            <td>{{ $bill->order->waiter->name ?? '-' }}</td>
                            <td>{{ $bill->cashier->name ?? '-' }}</td>
                            <td>{{ ucfirst($bill->payment_method) }}</td>
                            <td>{{ number_format($bill->discount_total, 2) }}</td>
                            <td>{{ number_format($bill->grand_total, 2) }}</td>
                            <td>{{ $bill->paid_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                No paid bills found.
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