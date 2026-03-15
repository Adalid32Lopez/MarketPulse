<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Listar productos de un negocio
    public function index(Business $business)
    {
        $products = $business->products()->with('category')->latest()->paginate(10);
        return view('products.index', compact('business', 'products'));
    }

    // Mostrar formulario de creación
    public function create(Business $business)
    {
        $categories = $business->categories;
        return view('products.create', compact('business', 'categories'));
    }

    // Guardar nuevo producto
    public function store(Request $request, Business $business)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'sku'         => 'nullable|string|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'is_active'   => 'boolean',
        ]);

        $business->products()->create($validated);

        return redirect()
            ->route('businesses.products.index', $business)
            ->with('success', 'Producto creado correctamente.');
    }

    // Mostrar formulario de edición
    public function edit(Business $business, Product $product)
    {
        $categories = $business->categories;
        return view('products.edit', compact('business', 'product', 'categories'));
    }

    // Actualizar producto
    public function update(Request $request, Business $business, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'sku'         => 'nullable|string|unique:products,sku,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'is_active'   => 'boolean',
        ]);


        $product->update($validated);

        return redirect()
            ->route('businesses.products.index', $business)
            ->with('success', 'Producto actualizado correctamente.');
    }

    // Eliminar producto
    public function destroy(Business $business, Product $product)
    {
        $product->delete();

        return redirect()
            ->route('businesses.products.index', $business)
            ->with('success', 'Producto eliminado.');
    }
}
