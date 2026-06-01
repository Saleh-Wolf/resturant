@extends('layouts.admin')

@section('title', 'Edit Menu Item')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h1>Edit Menu Item</h1>

        <a href="{{ route('admin.menu-items.index') }}"
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

            <form action="{{ route('admin.menu-items.update', $menuItem) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Category</label>

                    <select name="category_id"
                            class="form-control"
                            required>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $menuItem->category_id == $category->id ? 'selected' : '' }}>

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
                           value="{{ old('name', $menuItem->name) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Price</label>

                    <input type="number"
                           step="0.01"
                           name="price"
                           class="form-control"
                           value="{{ old('price', $menuItem->price) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Description</label>

                    <textarea name="description"
                              class="form-control"
                              rows="4">{{ old('description', $menuItem->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Current Image</label>

                    <div class="mb-2">
                        @if($menuItem->image)
                            <img
                                src="{{ asset('storage/' . $menuItem->image) }}"
                                style="width:100px;height:100px;object-fit:cover;border-radius:10px;">
                        @else
                            No image
                        @endif
                    </div>

                    <input type="file"
                           name="image"
                           class="form-control">
                </div>

                <div class="form-check mb-3">

                    <input type="checkbox"
                           class="form-check-input"
                           name="is_available"
                           id="is_available"
                           {{ $menuItem->is_available ? 'checked' : '' }}>

                    <label class="form-check-label"
                           for="is_available">

                        Available

                    </label>

                </div>

                <button type="submit"
                        class="btn btn-warning">

                    Update Menu Item

                </button>

            </form>

        </div>
    </div>

</div>
@endsection