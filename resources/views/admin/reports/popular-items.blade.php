@extends('layouts.admin')

@section('title', 'Popular Items Report')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Popular Items Report</h1>

    <form method="GET"
          action="{{ route('admin.reports.popular-items') }}"
          class="card card-body mb-4">

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
                    <option value="">Name</option>
                    <option value="orders" {{ request('sort') === 'orders' ? 'selected' : '' }}>Orders Count</option>
                    <option value="quantity" {{ request('sort') === 'quantity' ? 'selected' : '' }}>Quantity Sold</option>
                    <option value="revenue" {{ request('sort') === 'revenue' ? 'selected' : '' }}>Revenue</option>
                </select>
            </div>

            <div class="col-md-3">
                <label>&nbsp;</label>
                <div>
                    <button class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.reports.popular-items') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </div>

    </form>

    <div class="card mb-4">
        <div class="card-header">Items Statistics</div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Times Ordered</th>
                        <th>Quantity Sold</th>
                        <th>Revenue</th>
                        <th>% of Sales</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($items as $item)
                        @php
                            $revenue = $item->revenue_generated ?? 0;
                            $percentage = $totalRevenue > 0
                                ? ($revenue / $totalRevenue) * 100
                                : 0;
                        @endphp

                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category->name ?? '-' }}</td>
                            <td>{{ $item->subcategory->name ?? '-' }}</td>
                            <td>{{ $item->times_ordered ?? 0 }}</td>
                            <td>{{ $item->total_quantity_sold ?? 0 }}</td>
                            <td>{{ number_format($revenue, 2) }} EGP</td>
                            <td>{{ number_format($percentage, 2) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links() }}
        </div>
    </div>

    <div class="row">

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Best Sellers</div>
                <div class="card-body">
                    <ol>
                        @forelse($bestSellers as $item)
                            <li>
                                {{ $item->name }}
                                — {{ $item->total_quantity_sold ?? 0 }}
                            </li>
                        @empty
                            <li>No data</li>
                        @endforelse
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Slow Moving Items</div>
                <div class="card-body">
                    <ol>
                        @forelse($slowMovingItems as $item)
                            <li>
                                {{ $item->name }}
                                — {{ $item->total_quantity_sold ?? 0 }}
                            </li>
                        @empty
                            <li>No data</li>
                        @endforelse
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Never Ordered Items</div>
                <div class="card-body">
                    <ol>
                        @forelse($neverOrderedItems as $item)
                            <li>{{ $item->name }}</li>
                        @empty
                            <li>No data</li>
                        @endforelse
                    </ol>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection