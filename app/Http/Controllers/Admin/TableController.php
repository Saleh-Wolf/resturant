<?php

namespace App\Http\Controllers\Admin;

use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
                'string',
                'max:50',
                'unique:restaurant_tables,table_number',
            ],

            'type' => [
                'required',
                'in:public,private',
            ],

            'min_capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'max_capacity' => [
                'required',
                'integer',
                'gte:min_capacity',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:available,occupied,reserved',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['capacity'] = $validated['max_capacity'];
        $validated['qr_token'] = Str::random(32);

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
                'string',
                'max:50',
                'unique:restaurant_tables,table_number,' . $restaurantTable->getKey(),
            ],

            'type' => [
                'required',
                'in:public,private',
            ],

            'min_capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'max_capacity' => [
                'required',
                'integer',
                'gte:min_capacity',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:available,occupied,reserved',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['capacity'] = $validated['max_capacity'];

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

    public function qr(RestaurantTable $table)
{
    return view(
        'admin.tables.qr',
        compact('table')
    );
}

public function downloadQr(RestaurantTable $table)
{
    $url = route('public.table.menu', [
        $table->table_number,
        $table->qr_token,
    ]);

    $svg = QrCode::size(1000)->generate($url);

    return response($svg)
        ->header('Content-Type', 'image/svg+xml')
        ->header(
            'Content-Disposition',
            'attachment; filename="table-' . $table->table_number . '-qr.svg"'
        );
}

public function regenerateQr(RestaurantTable $table)
{
    $table->update([
        'qr_token' => (string) Str::uuid(),
    ]);

    return redirect()
        ->route('admin.tables.qr', $table)
        ->with('success', 'QR code regenerated successfully');
}

public function bulkGenerateQr()
{
    RestaurantTable::whereNull('qr_token')
        ->orWhere('qr_token', '')
        ->get()
        ->each(function ($table) {
            $table->update([
                'qr_token' => (string) Str::uuid(),
            ]);
        });

    return back()
        ->with('success', 'QR codes generated for tables successfully');
}
}
