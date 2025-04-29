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
                'name' => 'required|string|max:255|unique:products',
                'stock' => 'required|integer|min:1',
                'category' => 'required|string',
            ]);

            Product::create([
                'name' => $validated['name'],
                'stock' => $validated['stock'],
                'category' => $validated['category'],
                'isActive' => true
            ]);

            return response()->json([
                'message' => 'Product added successfully'
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
}
