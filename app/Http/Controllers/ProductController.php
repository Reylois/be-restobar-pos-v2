<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /********************** Helper functions for Ingredients *************************************************************/
    public function showIngredients() {
        try {
            $products = Product::where('isActive', 1)
                               ->where('category', 'ingredients')->get();

            return response()->json([
                'status' => 'success',
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching ingredients: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching ingredients',
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
                    'message' => 'Ingredient added successfully',
                    'product' => $product
                ]);
            }

            if ($product && $product->isActive) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ingredient with the same name already exists.'
                ], 409);
            }

            $newProduct = Product::create([
                'name' => $validated['name'],
                'stock' => $validated['stock'],
                'category' => $validated['category']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'New Ingredient created',
                'product' => $newProduct
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding ingredient: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding ingredient',
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

    public function updateIngredient(Request $request, $id) 
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'stock' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($id);
            $product->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient updated successfully',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating ingredients: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error updating ingredient',
                'error' => $e->getMessage() 
            ], 500);
        }
    }


    /********************** Helper functions for Beverages *************************************************************/

    public function showBeverages() {
        try {
            $products = Product::where('isActive', 1)
                               ->where('category', 'beverages')->get();

            return response()->json([
                'status' => 'success',
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching beverages: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching beverages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addBeverage(Request $request)
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
                    'message' => 'Beverage added successfully',
                    'product' => $product
                ]);
            }

            if ($product && $product->isActive) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Beverage with the same name already exists.'
                ], 409);
            }

            $newProduct = Product::create([
                'name' => $validated['name'],
                'stock' => $validated['stock'],
                'category' => $validated['category']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'New Beverage added',
                'product' => $newProduct
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding beverage: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding beverage',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteBeverage(Product $product) {
        try {
            $product->update(['isActive' => 0]);

            return response()->json([
                'status' => 'success',
                'message' => 'Beverage deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting beverage: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting beverage',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updateBeverage(Request $request, $id) 
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'stock' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($id);
            $product->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Beverage updated successfully',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating beverage: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error updating beverage',
                'error' => $e->getMessage() 
            ], 500);
        }
    }

    /********************** Helper functions for Desserts *************************************************************/
    public function showDesserts() {
        try {
            $products = Product::where('isActive', 1)
                               ->where('category', 'desserts')->get();

            return response()->json([
                'status' => 'success',
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching desserts: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching desserts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addDessert(Request $request)
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
                    'message' => 'Dessert added successfully',
                    'product' => $product
                ]);
            }

            if ($product && $product->isActive) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dessert with the same name already exists.'
                ], 409);
            }

            $newProduct = Product::create([
                'name' => $validated['name'],
                'stock' => $validated['stock'],
                'category' => $validated['category']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'New Dessert added',
                'product' => $newProduct
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding dessert: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding dessert',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteDessert(Product $product) {
        try {
            $product->update(['isActive' => 0]);

            return response()->json([
                'status' => 'success',
                'message' => 'Dessert deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting dessert: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting dessert',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updateDessert(Request $request, $id) 
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'stock' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($id);
            $product->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Dessert updated successfully',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating dessert: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error updating dessert',
                'error' => $e->getMessage() 
            ], 500);
        }
    }

    /********************** Helper functions for Others *************************************************************/

    public function showOthers() {
        try {
            $products = Product::where('isActive', 1)
                               ->where('category', 'others')->get();

            return response()->json([
                'status' => 'success',
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching items: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addOther(Request $request)
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
                    'message' => 'Item added successfully',
                    'product' => $product
                ]);
            }

            if ($product && $product->isActive) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Item with the same name already exists.'
                ], 409);
            }

            $newProduct = Product::create([
                'name' => $validated['name'],
                'stock' => $validated['stock'],
                'category' => $validated['category']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'New item added',
                'product' => $newProduct
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding item: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteOther(Product $product) {
        try {
            $product->update(['isActive' => 0]);

            return response()->json([
                'status' => 'success',
                'message' => 'Item deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting item: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting item',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updateOther(Request $request, $id) 
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'stock' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($id);
            $product->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Item updated successfully',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating item: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error updating item',
                'error' => $e->getMessage() 
            ], 500);
        }
    }
}
