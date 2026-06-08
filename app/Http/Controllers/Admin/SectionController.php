<?php

namespace App\Http\Controllers\Admin;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::latest()
            ->paginate(10);

        return view(
            'admin.sections.index',
            compact('sections')
        );
    }

    public function create()
    {
        return view('admin.sections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:sections,name',
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
        Section::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'display_order' => $validated['display_order'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.sections.index')
            ->with('success', 'Section created successfully');
    }

    public function edit(Section $section)
{
    return view(
        'admin.sections.edit',
        compact('section')
    );
}

public function update(Request $request, Section $section)
{
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'unique:sections,name,' . $section->getKey(),
        ],
        'description' => ['nullable', 'string'],
        'display_order' => ['required', 'integer', 'min:0'],
    ]);

    $section->update([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'display_order' => $validated['display_order'],
        'is_active' => $request->has('is_active'),
    ]);

    return redirect()
        ->route('admin.sections.index')
        ->with('success', 'Section updated successfully');
}

public function destroy(Section $section)
{
    if ($section->categories()->exists()) {
        return back()
            ->with('error', 'Cannot delete section because it has categories.');
    }

    $section->delete();

    return redirect()
        ->route('admin.sections.index')
        ->with('success', 'Section deleted successfully');
}

}
