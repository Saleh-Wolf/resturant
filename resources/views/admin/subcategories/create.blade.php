@extends('layouts.admin')

@section('title', 'Create Subcategory')

@section('content')

    <div class="container-fluid">

        <h1 class="mb-4">
            Create Subcategory
        </h1>

        <div class="card">

            <div class="card-body">

                <form action="{{ route('admin.subcategories.store') }}" method="POST">

                    @csrf

                    <div class="form-group">

                        <label>
                            Category
                        </label>

                        <select name="category_id" class="form-control" required>

                            <option value="">
                                Select Category
                            </option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="form-group">

                        <label>
                            Name
                        </label>

                        <input type="text" name="name" class="form-control" required>

                    </div>

                    <div class="form-group">

                        <label>
                            Description
                        </label>

                        <textarea name="description" class="form-control" rows="3"></textarea>

                    </div>

                    <div class="form-group">

                        <label>
                            Display Order
                        </label>

                        <input type="number" name="display_order" value="0" class="form-control">

                    </div>

                    <div class="form-check mb-3">

                        <input type="checkbox" name="is_active" class="form-check-input" checked>

                        <label class="form-check-label">
                            Active
                        </label>

                    </div>

                    <button type="submit" class="btn btn-primary">

                        Save Subcategory

                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection
