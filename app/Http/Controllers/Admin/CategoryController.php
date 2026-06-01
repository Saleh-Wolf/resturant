<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name'
            ],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
{
    return view(
        'admin.categories.edit',
        compact('category')
    );
}

public function update(Request $request, Category $category)
{
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'unique:categories,name,' . $category->getKey(),
        ],
    ]);

    $category->update([
        'name' => $validated['name'],
        'slug' => Str::slug($validated['name']),
    ]);

    return redirect()
        ->route('admin.categories.index')
        ->with('success', 'Category updated successfully');
}

public function destroy(Category $category)
{
    $category->delete();

    return redirect()
        ->route('admin.categories.index')
        ->with('success', 'Category deleted successfully');
}

 
}
