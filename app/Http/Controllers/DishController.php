<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;

class DishController extends Controller
{
     public function index()
    {
        return Dish::with('ingredients')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:dishes,name',
            'price' => 'required|numeric',
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

        $dish = Dish::create($validated);

        // Attach ingredients with quantities if provided
        if (!empty($validated['ingredients'])) {
            $syncData = collect($validated['ingredients'])->mapWithKeys(function ($item) {
                return [$item['id'] => ['quantity' => $item['quantity']]];
            });
            $dish->ingredients()->sync($syncData);
        }

        return response()->json($dish->load('ingredients'), 201);
    }

    public function show(Dish $dish)
    {
        return $dish->load('ingredients');
    }

    public function update(Request $request, Dish $dish)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:dishes,name,' . $dish->id,
            'price' => 'required|numeric',
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

        $dish->update($validated);

        if (!empty($validated['ingredients'])) {
            $syncData = collect($validated['ingredients'])->mapWithKeys(function ($item) {
                return [$item['id'] => ['quantity' => $item['quantity']]];
            });
            $dish->ingredients()->sync($syncData);
        }

        return response()->json($dish->load('ingredients'));
    }

    public function destroy(Dish $dish)
    {
        $dish->delete();
        return response()->json(['message' => 'Dish deleted']);
    }
}
