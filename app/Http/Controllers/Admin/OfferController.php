<?php

namespace App\Http\Controllers\Admin;

use App\Models\Offer;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::withCount('menuItems')
            ->latest()
            ->paginate(10);

        return view(
            'admin.offers.index',
            compact('offers')
        );
    }

    public function create()
    {
        $menuItems = MenuItem::where('is_available', true)
            ->get();

        return view(
            'admin.offers.create',
            compact('menuItems')
        );
    }

     public function store(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'description' => ['nullable', 'string'],
        'discount_type' => ['required', 'in:percentage,fixed'],
        'discount_value' => ['required', 'numeric', 'min:0'],
        'start_date' => ['required', 'date'],
        'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        'menu_items' => ['required', 'array', 'min:1'],
        'menu_items.*' => ['exists:menu_items,id'],
    ]);

    $offer = Offer::create([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'discount_type' => $validated['discount_type'],
        'discount_value' => $validated['discount_value'],
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
        'is_active' => $request->has('is_active'),
        'display_on_menu' => $request->has('display_on_menu'),
    ]);

    $offer->menuItems()->sync(
        $validated['menu_items']
    );

    return redirect()
        ->route('admin.offers.index')
        ->with(
            'success',
            'Offer created successfully.'
        );
}
}