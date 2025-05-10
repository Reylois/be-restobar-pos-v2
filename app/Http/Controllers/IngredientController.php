<?php

namespace App\Http\Controllers;

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

        Ingredient::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient added successfully',
        ]);
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:ingredients,name,' . $ingredient->id,
            'stock' => 'required|numeric|min:0',
            'low_stock_threshold' => 'nullable|numeric|min:0',
            'isActive' => 'boolean',
        ]);

        $ingredient->update($validated);

        return response()->json($ingredient);
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return response()->json(['message' => 'Ingredient deleted']);
    }
}
