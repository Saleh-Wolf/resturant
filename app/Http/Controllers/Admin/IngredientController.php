<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ingredient;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::latest()
            ->paginate(15);

        return view(
            'admin.ingredients.index',
            compact('ingredients')
        );
    }

    public function create()
    {
        return view('admin.ingredients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:ingredients,name',
            ],
            'unit' => [
                'required',
                'in:gram,kg,ml,liter,piece',
            ],
            'current_stock' => [
                'required',
                'numeric',
                'min:0',
            ],
            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],
            'cost_per_unit' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'is_active' => [
                'nullable',
            ],
        ]);

        Ingredient::create([
            'name' => $validated['name'],
            'unit' => $validated['unit'],
            'current_stock' => $validated['current_stock'],
            'minimum_stock' => $validated['minimum_stock'],
            'cost_per_unit' => $validated['cost_per_unit'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.ingredients.index')
            ->with('success', 'Ingredient created successfully');
    }

    public function edit(Ingredient $ingredient)
    {
        return view(
            'admin.ingredients.edit',
            compact('ingredient')
        );
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:ingredients,name,' . $ingredient->id,
            ],
            'unit' => [
                'required',
                'in:gram,kg,ml,liter,piece',
            ],
            'current_stock' => [
                'required',
                'numeric',
                'min:0',
            ],
            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],
            'cost_per_unit' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'is_active' => [
                'nullable',
            ],
        ]);

        $ingredient->update([
            'name' => $validated['name'],
            'unit' => $validated['unit'],
            'current_stock' => $validated['current_stock'],
            'minimum_stock' => $validated['minimum_stock'],
            'cost_per_unit' => $validated['cost_per_unit'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.ingredients.index')
            ->with('success', 'Ingredient updated successfully');
    }

    public function destroy(Ingredient $ingredient)
    {
        if ($ingredient->menuItems()->exists()) {
            return back()
                ->with(
                    'error',
                    'Cannot delete ingredient because it is used in menu items.'
                );
        }

        $ingredient->delete();

        return redirect()
            ->route('admin.ingredients.index')
            ->with('success', 'Ingredient deleted successfully');
    }

    public function restock(Ingredient $ingredient)
{
    return view(
        'admin.ingredients.restock',
        compact('ingredient')
    );
}

public function storeRestock(Request $request, Ingredient $ingredient)
{
    $validated = $request->validate([
        'quantity' => ['required', 'numeric', 'min:0.01'],
        'notes' => ['nullable', 'string'],
    ]);

    DB::transaction(function () use ($ingredient, $validated) {
        $ingredient->increment('current_stock', $validated['quantity']);

        StockMovement::create([
            'ingredient_id' => $ingredient->id,
            'type' => 'addition',
            'quantity' => $validated['quantity'],
            'reference_type' => Ingredient::class,
            'reference_id' => $ingredient->id,
            'notes' => $validated['notes'] ?? 'Stock added manually',
        ]);
    });

    return redirect()
        ->route('admin.ingredients.index')
        ->with('success', 'Stock added successfully');
}



}