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
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'price' => 'required|numeric|decimal:0,2|min:1',
            ]);

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('menu-images', 'public');
            }

            $productList = ProductList::where('name', $validated['name'])->first();

            if ($productList && !$productList->isActive) {
                // Re-enable and update the existing product
                $productList->update([
                    'category' => $validated['category'],
                    'imagePath' => $imagePath,
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
                'imagePath' => $imagePath,
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
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'price' => 'required|numeric|decimal:0,2|min:1',
            ]);

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('menu-images', 'public');
            }

            $productList = ProductList::where('name', $validated['name'])->first();

            if ($productList && !$productList->isActive) {
                // Re-enable and update the existing product
                $productList->update([
                    'category' => $validated['category'],
                    'imagePath' => $imagePath,
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
                'imagePath' => $imagePath,
                'category' => $validated['category'],
                'price' => $validated['price'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Beverage dish added',
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
    
      /********************** Helper functions for Dessert List *************************************************************/
      public function showDessertList() {
        try {
            $productList = ProductList::where('isActive', 1)
                               ->where('category', 'desserts')->get();

            return response()->json([
                'status' => 'success',
                'data' => $productList,
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

    public function addDessertList(Request $request)
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
                    'message' => 'Desserts added successfully',
                    'product' => $productList
                ]);
            }

            if ($productList && $productList->isActive) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Desserts with the same name already exists.'
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
                'message' => 'Dessert added',
                'product' => $newproductList
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

    public function deleteDessertList(ProductList $productList) {
        try {
            \Log::info($productList);
            $productList->update(['isActive' => 0]);

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

    public function updateDessertList(Request $request, $id) 
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
                'message' => 'Dessert updated successfully',
                'product' => $productList
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

        /********************** Helper functions for Item List *************************************************************/
        public function showItemList() {
        try {
            $productList = ProductList::where('isActive', 1)
                                ->where('category', 'others')->get();

            return response()->json([
                'status' => 'success',
                'data' => $productList,
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

    public function addItemList(Request $request)
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
                    'message' => 'Item added successfully',
                    'product' => $productList
                ]);
            }

            if ($productList && $productList->isActive) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Item with the same name already exists.'
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
                'message' => 'Item added',
                'product' => $newproductList
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

    public function deleteItemList(ProductList $productList) {
        try {
            \Log::info($productList);
            $productList->update(['isActive' => 0]);

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

    public function updateItemList(Request $request, $id) 
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
                'message' => 'Item updated successfully',
                'product' => $productList
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

    /******************************************  Fetch Products by Category   *********************************************** */
    // In your controller (e.g., ProductController.php)
    public function showByCategory($category)
    {
        try {
            $validCategories = ['mainDish', 'beverages', 'desserts', 'others'];
            if (!in_array($category, $validCategories)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid category'
                ], 400);
            }

            $productList = ProductList::where('isActive', 1)
                                    ->where('category', $category)
                                    ->get();

            return response()->json([
                'status' => 'success',
                'data' => $productList,
            ]);
        } catch (\Exception $e) {
            \Log::error("Error fetching $category: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
