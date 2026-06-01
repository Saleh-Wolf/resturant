@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="container-fluid">

        <h1 class="mb-4">
            Categories
        </h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">

                Create Category
            </a>
        </div>

        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categories as $category)
                            <tr>

                                <td>
                                    {{ $category->name }}
                                </td>

                                <td>
                                    {{ $category->slug }}
                                </td>

                                <td>
                                    @if ($category->is_active)
                                        <span class="badge badge-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="d-flex gap-2">

                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="btn btn-sm btn-warning">

                                        Edit
                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                        onsubmit="return confirm('Delete category?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger">

                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">

                                    No categories found

                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                {{ $categories->links() }}

            </div>
        </div>

    </div>
@endsection
