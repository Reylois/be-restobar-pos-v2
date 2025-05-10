<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::query();

            if ($request->has('category')) {
                $query->where('category', $request->category)
                    ->where('isActive', 1);
            }

            $products = $query->with('ingredients')->get();

            return response()->json($products);
        } catch (\Exception $e) {
            \Log::error('Error fetching ' . $request->category . 's');
            return response()->json([
                ''
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|',
                'price' => 'nullable|numeric',
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

            $product = Product::where('name', $validated['name'])->first();

            if ($product && !$product->isActive) {
                // Re-enable and update the existing product
                $product->update([
                    'stock' => $validated['stock'],
                    'isActive' => true
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => ucfirst($validated['category']) . ' added successfully'
                ]);
            }

            if ($product && $product->isActive) {
                return response()->json([
                    'status' => 'error',
                    'message' => ucfirst($request->input('category')) . ' with the same name already exists'
                ], 409);
            }

            $product = Product::create($validated);

            // Attach ingredients with quantities if provided
            if (!empty($validated['ingredients'])) {
                $syncData = collect($validated['ingredients'])->mapWithKeys(function ($item) {
                    return [$item['id'] => ['quantity' => $item['quantity']]];
                });
                $product->ingredients()->sync($syncData);
            }

            return response()->json([
                'status' => 'success',
                'message' => ucfirst($request->input('category')) . ' added successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding product: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding ' . $request->input('category')
            ]);
        }
    }
    
    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:products,name,' . $product->id,
                'price' => 'nullable|numeric',
                'category' => 'required|string',
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

            return response()->json([
                'status' => 'success',
                'message' => ucfirst($validated['category']) . ' updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating product: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating ' . $request->input('category')
            ]);
        }
    }

    public function disable(Product $product, Request $request) {
        try {   
            $product->update(['isActive' => false]);

            return response()->json([
                'status' => 'success',
                'message' => ucfirst($request->input('category')) . ' deleted sucessfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error disabling product: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting ' . $request->input('category'),
                'error' => $e
            ], 500);
        }
    }
}
