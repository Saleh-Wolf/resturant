@extends('layouts.admin')

@section('title', 'Subcategories')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4">
        Subcategories
    </h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.subcategories.create') }}"
           class="btn btn-primary">
            Create Subcategory
        </a>
    </div>

    <div class="card">

        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($subcategories as $subcategory)

                        <tr>

                            <td>
                                {{ $subcategory->category->section->name ?? '-' }}
                            </td>

                            <td>
                                {{ $subcategory->category->name }}
                            </td>

                            <td>
                                {{ $subcategory->name }}
                            </td>

                            <td>
                                @if($subcategory->is_active)
                                    <span class="badge badge-success">
                                        Active
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td>

                                <div class="d-flex">

                                    <a href="{{ route('admin.subcategories.edit', $subcategory) }}"
                                       class="btn btn-sm btn-warning mr-1">

                                        Edit

                                    </a>

                                    <form action="{{ route('admin.subcategories.destroy', $subcategory) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete subcategory?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5"
                                class="text-center">

                                No subcategories found

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{ $subcategories->links() }}

        </div>

    </div>

</div>

@endsection