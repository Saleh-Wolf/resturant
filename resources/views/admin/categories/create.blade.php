@extends('layouts.admin')

@section('title', 'Create Category')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h1>Create Category</h1>

        <a href="{{ route('admin.categories.index') }}"
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

            <form action="{{ route('admin.categories.store') }}"
                  method="POST">

                @csrf

                <div class="form-group">
                    <label>Category Name</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}"
                           required>
                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Create Category

                </button>

            </form>

        </div>
    </div>

</div>
@endsection
