<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index()
    {
        return Ingredient::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:ingredients,name',
            'stock' => 'required|numeric|min:0',
            'low_stock_threshold' => 'nullable|numeric|min:0',
            'isActive' => 'boolean',
        ]);

        $ingredient = Ingredient::create($validated);

        return response()->json($ingredient, 201);
    }

    public function show(Ingredient $ingredient)
    {
        return $ingredient;
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
