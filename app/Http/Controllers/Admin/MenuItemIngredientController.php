<?php

namespace App\Http\Controllers\Admin;

use App\Models\MenuItem;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuItemIngredientController extends Controller
{
    public function edit(MenuItem $menuItem)
    {
        $ingredients = Ingredient::where('is_active', true)
            ->orderBy('name')
            ->get();

        $menuItem->load('ingredients');

        return view(
            'admin.menu-items.ingredients',
            compact(
                'menuItem',
                'ingredients'
            )
        );
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'ingredients' => [
                'nullable',
                'array',
            ],
            'ingredients.*.ingredient_id' => [
                'required',
                'exists:ingredients,id',
            ],
            'ingredients.*.quantity_required' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ]);

        $syncData = [];

        foreach ($validated['ingredients'] ?? [] as $ingredientData) {
            $syncData[$ingredientData['ingredient_id']] = [
                'quantity_required' => $ingredientData['quantity_required'],
            ];
        }

        $menuItem->ingredients()->sync($syncData);

        return redirect()
            ->route('admin.menu-items.index')
            ->with('success', 'Menu item ingredients updated successfully');
    }
}