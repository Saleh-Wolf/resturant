@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
    <div class="container-fluid">

        <h1 class="mb-4">Users Management</h1>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>

            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>

            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">

                Create User
            </a>
        </div>
        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>

                                <td>
                                    <span class="badge badge-primary">
                                        {{ $user->role }}
                                    </span>
                                </td>

                                <td>{{ $user->phone ?? '-' }}</td>

                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td class="d-flex gap-2">

                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">

                                        Edit
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?')">

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
                                <td colspan="6" class="text-center text-muted">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $users->links() }}

            </div>
        </div>

    </div>
@endsection
