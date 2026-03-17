<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Business $business)
    {
        $categories = $business->categories()->latest()->paginate(10);
        return view('categories.index', compact('business', 'categories'));
    }

    public function create(Business $business)
    {
        return view('categories.create', compact('business'));
    }

    public function store(Request $request, Business $business)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $business->categories()->create($validated);

        return redirect()
            ->route('businesses.categories.index', $business)
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Business $business, Category $category)
    {
        return view('categories.edit', compact('business', 'category'));
    }

    public function update(Request $request, Business $business, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()
            ->route('businesses.categories.index', $business)
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Business $business, Category $category)
    {
        $category->delete();

        return redirect()
            ->route('businesses.categories.index', $business)
            ->with('success', 'Categoría eliminada.');
    }
}