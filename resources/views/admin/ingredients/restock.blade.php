@extends('layouts.admin')

@section('title', 'Restock Ingredient')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h1>Restock Ingredient</h1>

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

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Ingredient:</strong> {{ $ingredient->name }}</p>
            <p><strong>Current Stock:</strong> {{ number_format($ingredient->current_stock, 2) }} {{ $ingredient->unit }}</p>
            <p><strong>Minimum Stock:</strong> {{ number_format($ingredient->minimum_stock, 2) }} {{ $ingredient->unit }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.ingredients.store-restock', $ingredient) }}"
                  method="POST">

                @csrf

                <div class="form-group">
                    <label>Quantity To Add</label>

                    <input type="number"
                           step="0.01"
                           min="0.01"
                           name="quantity"
                           class="form-control"
                           value="{{ old('quantity') }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Notes</label>

                    <textarea name="notes"
                              class="form-control"
                              rows="3"
                              placeholder="Example: Purchase from supplier">{{ old('notes') }}</textarea>
                </div>

                <button type="submit"
                        class="btn btn-success">
                    Add Stock
                </button>

            </form>

        </div>
    </div>

</div>
@endsection