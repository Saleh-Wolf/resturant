@extends('layouts.admin')

@section('title', 'Edit Subcategory')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Edit Subcategory</h1>

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

            <form action="{{ route('admin.subcategories.update', $subcategory) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Category</label>

                    <select name="category_id" class="form-control" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $subcategory->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->section->name ?? 'No Section' }}
                                →
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Name</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $subcategory->name) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Description</label>

                    <textarea name="description"
                              class="form-control"
                              rows="3">{{ old('description', $subcategory->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Display Order</label>

                    <input type="number"
                           name="display_order"
                           class="form-control"
                           value="{{ old('display_order', $subcategory->display_order) }}"
                           min="0">
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox"
                           name="is_active"
                           class="form-check-input"
                           id="is_active"
                           {{ old('is_active', $subcategory->is_active) ? 'checked' : '' }}>

                    <label class="form-check-label" for="is_active">
                        Active
                    </label>
                </div>

                <button type="submit" class="btn btn-warning">
                    Update Subcategory
                </button>

                <a href="{{ route('admin.subcategories.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>
    </div>

</div>
@endsection