@extends('layouts.admin')

@section('title', 'Sections')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4">
        Sections
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
        <a href="{{ route('admin.sections.create') }}"
           class="btn btn-primary">
            Create Section
        </a>
    </div>

    <div class="card">

        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($sections as $section)

                        <tr>

                            <td>
                                {{ $section->name }}
                            </td>

                            <td>
                                {{ $section->description ?? '-' }}
                            </td>

                            <td>
                                {{ $section->display_order }}
                            </td>

                            <td>
                                @if($section->is_active)
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

                                    <a href="{{ route('admin.sections.edit', $section) }}"
                                       class="btn btn-sm btn-warning mr-1">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.sections.destroy', $section) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this section?')">

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
                            <td colspan="5" class="text-center">
                                No sections found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{ $sections->links() }}

        </div>

    </div>

</div>

@endsection