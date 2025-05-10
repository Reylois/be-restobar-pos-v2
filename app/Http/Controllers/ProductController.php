<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
     public function index()
    {
        return Product::with('ingredients')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:dishes,name',
            'price' => 'required|numeric',
            'category' => 'nullable|string',
            'imagePath' => 'nullable|string',
            'track_stock' => 'boolean',
            'stock' => 'nullable|numeric',
            'available' => 'boolean',
            'isActive' => 'boolean',
            'ingredients' => 'array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
        ]);

        $product = Product::create($validated);

        // Attach ingredients with quantities if provided
        if (!empty($validated['ingredients'])) {
            $syncData = collect($validated['ingredients'])->mapWithKeys(function ($item) {
                return [$item['id'] => ['quantity' => $item['quantity']]];
            });
            $product->ingredients()->sync($syncData);
        }

        return response()->json($product->load('ingredients'), 201);
    }

    public function show(Product $product)
    {
        return $product->load('ingredients');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:Productes,name,' . $product->id,
            'price' => 'required|numeric',
            'category' => 'nullable|string',
            'imagePath' => 'nullable|string',
            'track_stock' => 'boolean',
            'stock' => 'nullable|numeric',
            'available' => 'boolean',
            'isActive' => 'boolean',
            'ingredients' => 'array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
        ]);

        $product->update($validated);

        if (!empty($validated['ingredients'])) {
            $syncData = collect($validated['ingredients'])->mapWithKeys(function ($item) {
                return [$item['id'] => ['quantity' => $item['quantity']]];
            });
            $product->ingredients()->sync($syncData);
        }

        return response()->json($product->load('ingredients'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }
}
