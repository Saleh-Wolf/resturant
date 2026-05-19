@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h1>Edit User</h1>

        <a href="{{ route('admin.users.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.users.update', $user->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Name</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $user->name) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Email</label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $user->email) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Phone</label>

                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="form-group">
                    <label>Role</label>

                    <select name="role"
                            class="form-control"
                            required>

                        <option value="admin"
                            {{ $user->role == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="waiter"
                            {{ $user->role == 'waiter' ? 'selected' : '' }}>
                            Waiter
                        </option>

                        <option value="cashier"
                            {{ $user->role == 'cashier' ? 'selected' : '' }}>
                            Cashier
                        </option>

                        <option value="kitchen_staff"
                            {{ $user->role == 'kitchen_staff' ? 'selected' : '' }}>
                            Kitchen Staff
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label>New Password (Optional)</label>

                    <input type="password"
                           name="password"
                           class="form-control">
                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Update User
                </button>

            </form>

        </div>
    </div>

</div>
@endsection
