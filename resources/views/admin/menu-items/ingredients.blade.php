@extends('layouts.admin')

@section('title', 'Menu Item Ingredients')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h1>
            Ingredients for: {{ $menuItem->name }}
        </h1>

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

            <form action="{{ route('admin.menu-items.ingredients.update', $menuItem) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div id="ingredients-wrapper">

                    @php
                        $existingIngredients = $menuItem->ingredients;
                    @endphp

                    @forelse($existingIngredients as $index => $ingredient)

                        <div class="row ingredient-row mb-3">

                            <div class="col-md-6">
                                <label>Ingredient</label>

                                <select name="ingredients[{{ $index }}][ingredient_id]"
                                        class="form-control"
                                        required>

                                    @foreach($ingredients as $availableIngredient)
                                        <option value="{{ $availableIngredient->id }}"
                                            {{ $ingredient->id == $availableIngredient->id ? 'selected' : '' }}>

                                            {{ $availableIngredient->name }}
                                            ({{ $availableIngredient->unit }})

                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Quantity Required</label>

                                <input type="number"
                                       step="0.01"
                                       min="0.01"
                                       name="ingredients[{{ $index }}][quantity_required]"
                                       class="form-control"
                                       value="{{ $ingredient->pivot->quantity_required }}"
                                       required>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button"
                                        class="btn btn-danger remove-row">
                                    Remove
                                </button>
                            </div>

                        </div>

                    @empty

                        <div class="row ingredient-row mb-3">

                            <div class="col-md-6">
                                <label>Ingredient</label>

                                <select name="ingredients[0][ingredient_id]"
                                        class="form-control">

                                    <option value="">
                                        Select Ingredient
                                    </option>

                                    @foreach($ingredients as $ingredient)
                                        <option value="{{ $ingredient->id }}">
                                            {{ $ingredient->name }}
                                            ({{ $ingredient->unit }})
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Quantity Required</label>

                                <input type="number"
                                       step="0.01"
                                       min="0.01"
                                       name="ingredients[0][quantity_required]"
                                       class="form-control"
                                       placeholder="Example: 100">
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button"
                                        class="btn btn-danger remove-row">
                                    Remove
                                </button>
                            </div>

                        </div>

                    @endforelse

                </div>

                <button type="button"
                        id="add-ingredient"
                        class="btn btn-info mb-3">
                    Add Ingredient
                </button>

                <br>

                <button type="submit"
                        class="btn btn-primary">
                    Save Ingredients
                </button>

            </form>

        </div>
    </div>

</div>

<script>
    let ingredientIndex = document.querySelectorAll('.ingredient-row').length;

    const ingredientsWrapper = document.getElementById('ingredients-wrapper');
    const addIngredientButton = document.getElementById('add-ingredient');

    const ingredientOptions = `
        <option value="">Select Ingredient</option>
        @foreach($ingredients as $ingredient)
            <option value="{{ $ingredient->id }}">
                {{ $ingredient->name }} ({{ $ingredient->unit }})
            </option>
        @endforeach
    `;

    addIngredientButton.addEventListener('click', function () {
        const row = document.createElement('div');

        row.className = 'row ingredient-row mb-3';

        row.innerHTML = `
            <div class="col-md-6">
                <label>Ingredient</label>

                <select name="ingredients[${ingredientIndex}][ingredient_id]"
                        class="form-control"
                        required>
                    ${ingredientOptions}
                </select>
            </div>

            <div class="col-md-4">
                <label>Quantity Required</label>

                <input type="number"
                       step="0.01"
                       min="0.01"
                       name="ingredients[${ingredientIndex}][quantity_required]"
                       class="form-control"
                       placeholder="Example: 100"
                       required>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="button"
                        class="btn btn-danger remove-row">
                    Remove
                </button>
            </div>
        `;

        ingredientsWrapper.appendChild(row);

        ingredientIndex++;
    });

    ingredientsWrapper.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-row')) {
            event.target.closest('.ingredient-row').remove();
        }
    });
</script>

@endsection