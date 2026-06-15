<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class MenuItemController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with([
            'category.section',
            'subcategory'
        ])
            ->latest()
            ->paginate(10);

        return view('admin.menu-items.index', compact('menuItems'));
    }

    public function create()
    {
        $categories = Category::with('section')
            ->orderBy('name')
            ->get();

        $subcategories = Subcategory::with('category')
            ->orderBy('name')
            ->get();

        return view(
            'admin.menu-items.create',
            compact('categories', 'subcategories')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['required', 'exists:subcategories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'is_available' => ['nullable'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request
                ->file('image')
                ->store('menu-items', 'public');
        }

        MenuItem::create([
            'category_id' => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'is_available' => $request->has('is_available'),
        ]);

        return redirect()
            ->route('admin.menu-items.index')
            ->with('success', 'Menu item created successfully');
    }

    public function edit(MenuItem $menuItem)
    {
        $categories = Category::with('section')
            ->orderBy('name')
            ->get();

        $subcategories = Subcategory::with('category')
            ->orderBy('name')
            ->get();

        return view(
            'admin.menu-items.edit',
            compact('menuItem', 'categories', 'subcategories')
        );
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['required', 'exists:subcategories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $imagePath = $menuItem->image;

        if ($request->hasFile('image')) {
            $imagePath = $request
                ->file('image')
                ->store('menu-items', 'public');
        }

        $menuItem->update([
            'category_id' => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'is_available' => $request->has('is_available'),
        ]);

        return redirect()
            ->route('admin.menu-items.index')
            ->with('success', 'Menu item updated successfully');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return redirect()
            ->route('admin.menu-items.index')
            ->with('success', 'Menu item deleted successfully');
    }
}