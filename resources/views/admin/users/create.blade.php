@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-3">
            <h1>Create User</h1>

            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                Back
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin.users.store') }}" method="POST">

                    @csrf

                    <div class="form-group">
                        <label>Name</label>

                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>

                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Phone</label>

                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>

                    <div class="form-group">
                        <label>Role</label>

                        <select name="role" class="form-control" required>

                            <option value="">
                                Select Role
                            </option>

                            <option value="admin">
                                Admin
                            </option>

                            <option value="waiter">
                                Waiter
                            </option>

                            <option value="cashier">
                                Cashier
                            </option>

                            <option value="kitchen_staff">
                                Kitchen Staff
                            </option>

                        </select>
                    </div>

                    <div class="form-group">
                        <label>Password</label>

                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Create User
                    </button>

                </form>

            </div>
        </div>

    </div>
@endsection
