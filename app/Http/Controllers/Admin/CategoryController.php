<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Section;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('section')
            ->latest()
            ->paginate(10);

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        $sections = Section::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return view(
            'admin.categories.create',
            compact('sections')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => [
                'required',
                'exists:sections,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name',
            ],
        ]);

        Category::create([
            'section_id' => $validated['section_id'],
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
        $sections = Section::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return view(
            'admin.categories.edit',
            compact('category', 'sections')
        );
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'section_id' => [
                'required',
                'exists:sections,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,' . $category->getKey(),
            ],
        ]);

        $category->update([
            'section_id' => $validated['section_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        if ($category->menuItems()->exists()) {
            return back()
                ->with('error', 'Cannot delete category because it has menu items.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }
}