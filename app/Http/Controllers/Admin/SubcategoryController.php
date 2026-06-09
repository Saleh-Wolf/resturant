<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')
            ->latest()
            ->paginate(10);

        return view(
            'admin.subcategories.index',
            compact('subcategories')
        );
    }

    public function create()
    {
        $categories = Category::orderBy('name')
            ->get();

        return view(
            'admin.subcategories.create',
            compact('categories')
        );
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'category_id' => [
            'required',
            'exists:categories,id',
        ],
        'name' => [
            'required',
            'string',
            'max:255',
            'unique:subcategories,name',
        ],
        'description' => [
            'nullable',
            'string',
        ],
        'display_order' => [
            'required',
            'integer',
            'min:0',
        ],
    ]);

    Subcategory::create([
        'category_id' => $validated['category_id'],
        'name' => $validated['name'],
        'slug' => Str::slug($validated['name']),
        'description' => $validated['description'] ?? null,
        'display_order' => $validated['display_order'],
        'is_active' => $request->has('is_active'),
    ]);

    return redirect()
        ->route('admin.subcategories.index')
        ->with('success', 'Subcategory created successfully');
}
}