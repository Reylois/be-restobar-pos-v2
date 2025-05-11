<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use App\Models\Ingredient; 

class IngredientController extends Controller
{
    public function index()
    {
        try {
            $ingredients = Ingredient::where('isActive', 1)->get();

            return response()->json($ingredients);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'error' => $e,
                'status' => 'error',
                'message' => 'Error fetching ingredients'
            ]);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:ingredients,name',
            'stock' => 'required|numeric|min:0',
            'low_stock_threshold' => 'nullable|numeric|min:0',
            'isActive' => 'boolean',
        ]);

        $ingredient = Ingredient::where('name', $validated['name'])->first();

        if ($ingredient && !$ingredient->isActive) {
            // Re-enable and update the existing product
            $ingredient->update([
                'stock' => $validated['stock'],
                'low_stock_threshold' => $validated['low_stock_threshold'],
                'isActive' => true
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient added successfully'
            ]);
        }

        if ($ingredient && $ingredient->isActive) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ingredient with the same name already exists'
            ], 409);
        }

        Ingredient::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient added successfully',
        ]);
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:ingredients,name,' . $ingredient->id,
                'stock' => 'required|numeric|min:0',
                'low_stock_threshold' => 'nullable|numeric|min:0',
                'isActive' => 'boolean',
            ]);

            $ingredient->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating ingredient: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating ingredient'
            ]);
        }
    }

    public function disable(Ingredient $ingredient) {
        try {   
            $ingredient->update(['isActive' => false]);

            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient deleted sucessfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error disabling ingredient: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting ingredient',
                'error' => $e
            ], 500);
        }
    }
}
