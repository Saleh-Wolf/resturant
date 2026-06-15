@extends('layouts.admin')

@section('title', 'Low Stock Report')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">
        Low Stock Report
    </h1>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Ingredient</th>
                        <th>Unit</th>
                        <th>Current Stock</th>
                        <th>Minimum Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($ingredients as $ingredient)

                        <tr>

                            <td>
                                {{ $ingredient->name }}
                            </td>

                            <td>
                                {{ ucfirst($ingredient->unit) }}
                            </td>

                            <td>
                                {{ number_format($ingredient->current_stock, 2) }}
                            </td>

                            <td>
                                {{ number_format($ingredient->minimum_stock, 2) }}
                            </td>

                            <td>

                                @if($ingredient->isLowStock())

                                    <span class="badge badge-danger">
                                        Low Stock
                                    </span>

                                @else

                                    <span class="badge badge-success">
                                        OK
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center">
                                No ingredients found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{ $ingredients->links() }}

        </div>
    </div>

</div>
@endsection