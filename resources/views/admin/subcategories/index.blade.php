@extends('layouts.admin')

@section('title', 'Subcategories')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4">
        Subcategories
    </h1>

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
                        <th>Category</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($subcategories as $subcategory)

                        <tr>

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

                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="text-center">
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