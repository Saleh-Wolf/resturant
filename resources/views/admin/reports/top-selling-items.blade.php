@extends('layouts.admin')

@section('title', 'Top Selling Items')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Top Selling Items</h1>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Menu Item</th>
                        <th>Total Sold Quantity</th>
                        <th>Total Revenue</th>
                        <th>Average Price</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($items as $index => $item)
                        <tr>
                            <td>{{ $items->firstItem() + $index }}</td>

                            <td>{{ $item->menuItem->name ?? '-' }}</td>

                            <td>{{ $item->total_quantity }}</td>

                            <td>
                                {{ number_format($item->total_revenue, 2) }} EGP
                            </td>

                            <td>
                                {{ number_format($item->average_price, 2) }} EGP
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No sales data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links() }}

        </div>
    </div>

</div>
@endsection