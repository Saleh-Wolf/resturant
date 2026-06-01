<?php

namespace App\Http\Controllers\Admin;

use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::latest()
            ->paginate(10);

        return view(
            'admin.tables.index',
            compact('tables')
        );
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => [
                'required',
                'unique:restaurant_tables,table_number'
            ],
            'capacity' => [
                'required',
                'integer',
                'min:1'
            ],
            'status' => [
                'required',
                'in:available,occupied,reserved'
            ],
        ]);

        RestaurantTable::create($validated);

        return redirect()
            ->route('admin.tables.index')
            ->with('success', 'Table created successfully');
    }

    public function edit(RestaurantTable $restaurantTable)
    {
        return view(
            'admin.tables.edit',
            compact('restaurantTable')
        );
    }

    public function update(
        Request $request,
        RestaurantTable $restaurantTable
    ) {
        $validated = $request->validate([
            'table_number' => [
                'required',
                'unique:restaurant_tables,table_number,' . $restaurantTable->getKey(),
            ],
            'capacity' => [
                'required',
                'integer',
                'min:1'
            ],
            'status' => [
                'required',
                'in:available,occupied,reserved'
            ],
        ]);

        $restaurantTable->update($validated);

        return redirect()
            ->route('admin.tables.index')
            ->with('success', 'Table updated successfully');
    }

    public function destroy(RestaurantTable $restaurantTable)
    {
        $restaurantTable->delete();

        return redirect()
            ->route('admin.tables.index')
            ->with('success', 'Table deleted successfully');
    }
}