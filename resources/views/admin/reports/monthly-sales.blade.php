@extends('layouts.admin')

@section('title', 'Monthly Sales Report')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">
        Monthly Sales Report
    </h1>

    <form method="GET"
          action="{{ route('admin.reports.sales.monthly') }}"
          class="card card-body mb-4">

        <div class="row">
            <div class="col-md-4">
                <label>Month</label>

                <input type="month"
                       name="month"
                       class="form-control"
                       value="{{ $month }}">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary btn-block">
                    Filter
                </button>
            </div>
        </div>

    </form>

    <div class="row mb-4">

        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalRevenue, 2) }}</h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $totalOrders }}</h3>
                    <p>Total Orders</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($averageOrderValue, 2) }}</h3>
                    <p>Average Order Value</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($averageDailyRevenue, 2) }}</h3>
                    <p>Average Daily Revenue</p>
                </div>
            </div>
        </div>

    </div>

    <div class="card mb-4">
        <div class="card-header">
            Previous Month Comparison
        </div>

        <div class="card-body">
            <p>
                <strong>Previous Month Revenue:</strong>
                {{ number_format($previousRevenue, 2) }} EGP
            </p>

            <p>
                <strong>Growth:</strong>

                @if($growthPercentage >= 0)
                    <span class="text-success">
                        +{{ number_format($growthPercentage, 2) }}%
                    </span>
                @else
                    <span class="text-danger">
                        {{ number_format($growthPercentage, 2) }}%
                    </span>
                @endif
            </p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            Daily Sales Trend
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($dailySales as $day)
                        <tr>
                            <td>{{ $day->date }}</td>
                            <td>{{ $day->orders_count }}</td>
                            <td>{{ number_format($day->revenue, 2) }} EGP</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                No sales found for this month.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Top 10 Items
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($topItems as $item)
                        <tr>
                            <td>{{ $item->menuItem->name ?? '-' }}</td>
                            <td>{{ $item->total_quantity }}</td>
                            <td>{{ number_format($item->total_revenue, 2) }} EGP</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                No items sold for this month.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection