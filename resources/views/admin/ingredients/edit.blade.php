@extends('layouts.admin')

@section('title', 'Edit Ingredient')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h1>Edit Ingredient</h1>

        <a href="{{ route('admin.ingredients.index') }}"
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

            <form action="{{ route('admin.ingredients.update', $ingredient) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Name</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $ingredient->name) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Unit</label>

                    <select name="unit"
                            class="form-control"
                            required>

                        @foreach(['gram', 'kg', 'ml', 'liter', 'piece'] as $unit)
                            <option value="{{ $unit }}"
                                {{ $ingredient->unit === $unit ? 'selected' : '' }}>
                                {{ ucfirst($unit) }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label>Current Stock</label>

                    <input type="number"
                           step="0.01"
                           name="current_stock"
                           class="form-control"
                           value="{{ old('current_stock', $ingredient->current_stock) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Minimum Stock</label>

                    <input type="number"
                           step="0.01"
                           name="minimum_stock"
                           class="form-control"
                           value="{{ old('minimum_stock', $ingredient->minimum_stock) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Cost Per Unit</label>

                    <input type="number"
                           step="0.01"
                           name="cost_per_unit"
                           class="form-control"
                           value="{{ old('cost_per_unit', $ingredient->cost_per_unit) }}">
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox"
                           name="is_active"
                           id="is_active"
                           class="form-check-input"
                           {{ $ingredient->is_active ? 'checked' : '' }}>

                    <label class="form-check-label"
                           for="is_active">
                        Active
                    </label>
                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Update Ingredient
                </button>

            </form>

        </div>
    </div>

</div>
@endsection