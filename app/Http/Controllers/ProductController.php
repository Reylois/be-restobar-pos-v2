<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function showIngredients() {
        try {
            $products = Product::where('isActive', 1)->where('category', 'ingredients')->get();

            return response()->json([
                'status' => 'success',
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching products: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addIngredient(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'stock' => 'required|integer|min:1',
                'category' => 'required|string',
            ]);

            $product = Product::where('name', $validated['name'])->first();

            if ($product && !$product->isActive) {
                // Re-enable and update the existing product
                $product->update([
                    'stock' => $validated['stock'],
                    'category' => $validated['category'],
                    'isActive' => true
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Product added successfully',
                    'product' => $product
                ]);
            }

            if ($product && $product->isActive) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product with the same name already exists.'
                ], 409);
            }

            $newProduct = Product::create([
                'name' => $validated['name'],
                'stock' => $validated['stock'],
                'category' => $validated['category']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'New product created',
                'product' => $newProduct
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating products: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteIngredient(Product $product) {
        try {
            $product->update(['isActive' => 0]);

            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting ingredient: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting ingredient',
                'error' => $e->getMessage()
            ]);
        }
    }
}
