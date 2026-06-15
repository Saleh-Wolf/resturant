@extends('layouts.admin')

@section('title', 'Menu Items')

@section('content')
    <div class="container-fluid">

        <h1 class="mb-4">Menu Items</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.menu-items.create') }}" class="btn btn-primary">
                Create Menu Item
            </a>
        </div>

        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Section</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($menuItems as $item)
                            <tr>
                                <td>
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}"
                                            style="width:70px;height:70px;object-fit:cover;border-radius:10px;">
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>{{ $item->name }}</td>

                                <td>
                                    {{ $item->category->section->name ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge badge-info">
                                        {{ $item->category->name ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $item->subcategory->name ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    {{ number_format($item->price, 2) }} EGP
                                </td>

                                <td>
                                    @if ($item->is_available)
                                        <span class="badge badge-success">
                                            Available
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            Unavailable
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex">

                                        <a href="{{ route('admin.menu-items.ingredients.edit', $item) }}"
                                            class="btn btn-sm btn-info">

                                            Ingredients

                                        </a>

                                        <a href="{{ route('admin.menu-items.edit', $item) }}"
                                            class="btn btn-sm btn-warning">

                                            Edit

                                        </a>

                                        <form action="{{ route('admin.menu-items.destroy', $item) }}" method="POST"
                                            onsubmit="return confirm('Delete item?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>

                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    No menu items found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $menuItems->links() }}

            </div>
        </div>

    </div>
@endsection
