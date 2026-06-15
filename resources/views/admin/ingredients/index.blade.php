@extends('layouts.admin')

@section('title', 'Ingredients')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-3">
            <h1>Ingredients</h1>

            <a href="{{ route('admin.ingredients.create') }}" class="btn btn-primary">
                Create Ingredient
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Unit</th>
                            <th>Current Stock</th>
                            <th>Minimum Stock</th>
                            <th>Cost / Unit</th>
                            <th>Stock Status</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($ingredients as $ingredient)
                            <tr>
                                <td>{{ $ingredient->name }}</td>
                                <td>{{ ucfirst($ingredient->unit) }}</td>
                                <td>{{ number_format($ingredient->current_stock, 2) }}</td>
                                <td>{{ number_format($ingredient->minimum_stock, 2) }}</td>
                                <td>{{ number_format($ingredient->cost_per_unit, 2) }}</td>

                                <td>
                                    @if ($ingredient->isLowStock())
                                        <span class="badge badge-danger">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="badge badge-success">
                                            OK
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($ingredient->is_active)
                                        <span class="badge badge-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.ingredients.edit', $ingredient) }}"
                                            class="btn btn-warning btn-sm mr-1">
                                            Edit
                                        </a>

                                        <a href="{{ route('admin.ingredients.restock', $ingredient) }}"
                                            class="btn btn-success btn-sm mr-1">
                                            Restock
                                        </a>

                                        <form action="{{ route('admin.ingredients.destroy', $ingredient) }}" method="POST"
                                            onsubmit="return confirm('Delete ingredient?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
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
