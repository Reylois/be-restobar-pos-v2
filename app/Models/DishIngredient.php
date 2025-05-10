<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishIngredient extends Model
{
    use HasFactory;

    protected $table = 'dish_ingredients';
    
    protected $fillable = [
        'dish_id',
        'ingredient_id',
        'quantity',
    ];

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
