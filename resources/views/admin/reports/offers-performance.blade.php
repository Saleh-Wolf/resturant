@extends('layouts.admin')

@section('title', 'Offers Performance Report')

@section('content')
    <div class="container-fluid">

        <h1 class="mb-4">
            Offers Performance Report
        </h1>

        <form method="GET" action="{{ route('admin.reports.offers-performance') }}" class="card card-body mb-4">

            <div class="row">

                <div class="col-md-3">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>

                <div class="col-md-3">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>

                <div class="col-md-3">
                    <label>Sort By</label>
                    <select name="sort" class="form-control">
                        <option value="">Default</option>
                        <option value="usage" {{ request('sort') === 'usage' ? 'selected' : '' }}>
                            Times Used
                        </option>
                        <option value="discount" {{ request('sort') === 'discount' ? 'selected' : '' }}>
                            Total Discount
                        </option>
                        <option value="revenue" {{ request('sort') === 'revenue' ? 'selected' : '' }}>
                            Revenue
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        Filter
                    </button>

                    <a href="{{ route('admin.reports.offers-performance') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>

            </div>

        </form>

        <div class="row mb-4">

            <div class="col-md-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $totalOffers }}</h3>
                        <p>Total Offers</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalUsage }}</h3>
                        <p>Total Usage</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ number_format($totalDiscount, 2) }}</h3>
                        <p>Total Discount</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($totalRevenue, 2) }}</h3>
                        <p>Total Revenue</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>Offer</th>
                            <th>Active Period</th>
                            <th>Items Count</th>
                            <th>Times Used</th>
                            <th>Total Discount</th>
                            <th>Revenue</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($offerStats as $stat)
                            <tr>
                                <td>
                                    {{ $stat['offer']->name }}
                                </td>

                                <td>
                                    {{ $stat['offer']->start_date ?? '-' }}
                                    -
                                    {{ $stat['offer']->end_date ?? '-' }}
                                </td>

                                <td>
                                    {{ $stat['items_count'] }}
                                </td>

                                <td>
                                    {{ $stat['times_used'] }}
                                </td>

                                <td>
                                    {{ number_format($stat['total_discount'], 2) }} EGP
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($stat['revenue'], 2) }} EGP
                                    </strong>
                                </td>

                                <td>
                                    @if ($stat['offer']->is_active)
                                        <span class="badge badge-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center">
                                    No offers found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

    </div>
@endsection
