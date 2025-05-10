<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $with = ['ingredients'];
    protected $appends = ['available_quantity'];

    protected $fillable = [
        'name',
        'price',
        'category',
        'imagePath',
        'track_stock',
        'stock',
        'available',
        'isActive',
    ];

     public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'dish_ingredients')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('isActive', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }

   public function getAvailableQuantityAttribute()
{
    // If tracking actual stock, return stock directly
    if ($this->track_stock) {
        return $this->stock;
    }

    // If the product has ingredients, calculate based on their stocks
    if ($this->ingredients->isNotEmpty()) {
        $quantities = [];

        foreach ($this->ingredients as $ingredient) {
            $requiredQty = $ingredient->pivot->quantity;
            if ($requiredQty == 0) continue;

            $availableByThisIngredient = floor($ingredient->stock / $requiredQty);
            $quantities[] = $availableByThisIngredient;
        }

        return min($quantities);
    }

    // Default fallback
    return 0;
}
}
