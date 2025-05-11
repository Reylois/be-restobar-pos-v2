<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::query();

            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            $products = $query->active()->get(); // uses scopeActive

            return response()->json($products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category' => $product->category,
                    'imagePath' => $product->imagePath,
                    'track_stock' => $product->track_stock,
                    'available_quantity' => $product->available_quantity,
                    'available' => $product->available_quantity > 0,
                    'ingredients' => $product->ingredients,
                ];
            }));
        } catch (\Exception $e) {
            \Log::error('Error fetching ' . $request->category . 's: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch products'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|in:main_dish,beverage,dessert,item',
                'stock' => 'nullable|numeric|min:0',
                'price' => 'nullable|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'ingredients' => 'sometimes|array',
                'ingredients.*.id' => 'required|exists:ingredients,id',
                'ingredients.*.quantity' => 'required|numeric|min:0',
            ]);

            $hasIngredients = !empty($validated['ingredients']);
            $trackStock = !$hasIngredients;

            $product = Product::where('name', $validated['name'])->first();

            // If reactivating an existing product
            if ($product && !$product->isActive) {
                $updateData = [
                    'category' => $validated['category'],
                    'track_stock' => $trackStock,
                    'isActive' => true,
                    'price' => $validated['price'] ?? 0,
                ];

                if ($trackStock && isset($validated['stock'])) {
                    $updateData['stock'] = $validated['stock'];
                }

                if ($request->hasFile('image')) {
                    if ($product->imagePath) {
                        Storage::delete($product->imagePath);
                    }
                    $updateData['imagePath'] = $request->file('image')->store('products', 'public');
                }

                $product->update($updateData);

                if ($hasIngredients) {
                    $syncData = collect($validated['ingredients'])->mapWithKeys(function ($item) {
                        return [$item['id'] => ['quantity' => $item['quantity']]];
                    });
                    $product->ingredients()->sync($syncData);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => str_replace('_', ' ',ucfirst($validated['category'])) . ' added successfully'
                ]);
            }

            if ($product && $product->isActive) {
                return response()->json([
                    'status' => 'error',
                    'message' =>str_replace('_', ' ',ucfirst($validated['category'])) . ' with the same name already exists'
                ], 409);
            }

            // New product creation
            $data = [
                'name' => $validated['name'],
                'category' => $validated['category'],
                'track_stock' => $trackStock,
                'price' => $validated['price'] ?? 0,
                'isActive' => true,
            ];

            if ($trackStock && isset($validated['stock'])) {
                $data['stock'] = $validated['stock'];
            }

            if ($request->hasFile('image')) {
                $data['imagePath'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($data);

            if ($hasIngredients) {
                $syncData = collect($validated['ingredients'])->mapWithKeys(function ($item) {
                    return [$item['id'] => ['quantity' => $item['quantity']]];
                });
                $product->ingredients()->sync($syncData);
            }

            return response()->json([
                'status' => 'success',
                'message' => str_replace('_', ' ',ucfirst($validated['category'])) . ' added successfully',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding product: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
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
            'stock' => 'nullable|numeric',
            'available' => 'boolean',
            'isActive' => 'boolean',
            'ingredients' => 'array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
        ]);

        $hasIngredients = !empty($validated['ingredients']);
        $trackStock = !$hasIngredients;

        $updateData = [
            'name' => $validated['name'],
            'price' => $validated['price'] ?? $product->price,
            'category' => $validated['category'],
            'imagePath' => $validated['imagePath'] ?? $product->imagePath,
            'stock' => $validated['stock'] ?? $product->stock,
            'available' => $validated['available'] ?? $product->available,
            'isActive' => $validated['isActive'] ?? $product->isActive,
            'track_stock' => $trackStock
        ];

        // Handle image upload if file is present
        if ($request->hasFile('image')) {
            if ($product->imagePath) {
                \Storage::disk('public')->delete($product->imagePath);
            }
            $path = $request->file('image')->store('products', 'public');
            $updateData['imagePath'] = $path;
        }

        $product->update($updateData);

        if ($hasIngredients) {
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
            'message' => 'Error updating ' . $request->input('category'),
            'error' => $e->getMessage()
        ], 500);
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
