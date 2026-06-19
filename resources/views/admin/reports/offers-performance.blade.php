@extends('layouts.admin')

@section('title', 'Offers Performance Report')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">
        Offers Performance Report
    </h1>

    <form method="GET"
          action="{{ route('admin.reports.offers-performance') }}"
          class="card card-body mb-4">

        <div class="row">
            <div class="col-md-4">
                <label>From Date</label>

                <input type="date"
                       name="from_date"
                       class="form-control"
                       value="{{ request('from_date') }}">
            </div>

            <div class="col-md-4">
                <label>To Date</label>

                <input type="date"
                       name="to_date"
                       class="form-control"
                       value="{{ request('to_date') }}">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary mr-2">
                    Filter
                </button>

                <a href="{{ route('admin.reports.offers-performance') }}"
                   class="btn btn-secondary">
                    Reset
                </a>
            </div>
        </div>

    </form>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Offer</th>
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
                                {{ $stat['items_count'] }}
                            </td>

                            <td>
                                {{ $stat['times_used'] }}
                            </td>

                            <td>
                                {{ number_format($stat['total_discount'], 2) }} EGP
                            </td>

                            <td>
                                {{ number_format($stat['revenue'], 2) }} EGP
                            </td>

                            <td>
                                @if($stat['offer']->is_active)
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
                            <td colspan="6"
                                class="text-center">
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