@extends('layouts.admin')

@section('title', 'Tables')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Tables</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.tables.create') }}"
           class="btn btn-primary">

            Create Table
        </a>
    </div>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>Table Number</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tables as $table)

                        <tr>

                            <td>
                                {{ $table->table_number }}
                            </td>

                            <td>
                                {{ $table->capacity }}
                            </td>

                            <td>
                                @if($table->status === 'available')
                                    <span class="badge badge-success">
                                        Available
                                    </span>

                                @elseif($table->status === 'occupied')
                                    <span class="badge badge-danger">
                                        Occupied
                                    </span>

                                @else
                                    <span class="badge badge-warning">
                                        Reserved
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex gap-2">

                                    <a href="{{ route('admin.tables.edit', $table) }}"
                                       class="btn btn-sm btn-warning">

                                        Edit
                                    </a>

                                    <form action="{{ route('admin.tables.destroy', $table) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete table?')">

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
                            <td colspan="4"
                                class="text-center">

                                No tables found

                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

            {{ $tables->links() }}

        </div>
    </div>

</div>
@endsection