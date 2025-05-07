<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductList;

class ProductListController extends Controller
{
     /********************** Helper functions for MainDish *************************************************************/
     public function showMainDish() {
        try {
            $productList = ProductList::where('isActive', 1)
                               ->where('category', 'mainDish')->get();

            return response()->json([
                'status' => 'success',
                'data' => $productList,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching main dish: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching main dish',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addMainDish(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string',
                'imagePath' => 'nullable|string|max:255',
                'price' => 'required|numeric|decimal:0,2|min:1',
            ]);

            $productList = ProductList::where('name', $validated['name'])->first();

            if ($productList && !$productList->isActive) {
                // Re-enable and update the existing product
                $productList->update([
                    'category' => $validated['category'],
                    'imagePath' => $validated['imagePath'],
                    'price' => $validated['price'],
                    'isActive' => true
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Main Dish added successfully',
                    'product' => $productList
                ]);
            }

            if ($productList && $productList->isActive) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Main Dish with the same name already exists.'
                ], 409);
            }

            $newproductList = ProductList::create([
                'name' => $validated['name'],
                'imagePath' => $validated['imagePath'],
                'category' => $validated['category'],
                'price' => $validated['price'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'New main dish added',
                'product' => $newproductList
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding main dish: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding main dish',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteMainDish(ProductList $productList) {
        try {
            \Log::info($productList);
            $productList->update(['isActive' => 0]);

            return response()->json([
                'status' => 'success',
                'message' => 'Main dish deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting main dish: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting main dish',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updateMainDish(Request $request, $id) 
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|decimal:0,2|min:1',
                'imagePath' => 'nullable|string|max:255',
            ]);

            $productList = ProductList::findOrFail($id);
            $productList->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Main dish updated successfully',
                'product' => $productList
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating main dish: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error updating main dish',
                'error' => $e->getMessage() 
            ], 500);
        }
    }   

    /********************** Helper functions for Beverage List *************************************************************/
    public function showBeverageList() {
        try {
            $productList = ProductList::where('isActive', 1)
                               ->where('category', 'beverages')->get();

            return response()->json([
                'status' => 'success',
                'data' => $productList,
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

    public function addBeverageList(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string',
                'imagePath' => 'nullable|string|max:255',
                'price' => 'required|numeric|decimal:0,2|min:1',
            ]);

            $productList = ProductList::where('name', $validated['name'])->first();

            if ($productList && !$productList->isActive) {
                // Re-enable and update the existing product
                $productList->update([
                    'category' => $validated['category'],
                    'imagePath' => $validated['imagePath'],
                    'price' => $validated['price'],
                    'isActive' => true
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Beverage added successfully',
                    'product' => $productList
                ]);
            }

            if ($productList && $productList->isActive) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Beverage with the same name already exists.'
                ], 409);
            }

            $newproductList = ProductList::create([
                'name' => $validated['name'],
                'imagePath' => $validated['imagePath'],
                'category' => $validated['category'],
                'price' => $validated['price'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Beverage added',
                'product' => $newproductList
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

    public function deleteBeverageList(ProductList $productList) {
        try {
            \Log::info($productList);
            $productList->update(['isActive' => 0]);

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

    public function updateBeverageList(Request $request, $id) 
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|decimal:0,2|min:1',
                'imagePath' => 'nullable|string|max:255',
            ]);

            $productList = ProductList::findOrFail($id);
            $productList->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Beverage updated successfully',
                'product' => $productList
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
}
