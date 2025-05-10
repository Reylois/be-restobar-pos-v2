<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishIngredient extends Model
{
    use HasFactory;

    protected $table = 'dish_ingredients';
    
    protected $fillable = [
        'product_id',
        'ingredient_id',
        'quantity',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
