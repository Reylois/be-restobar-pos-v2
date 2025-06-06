<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'low_stock_threshold',
        'isActive',
    ];

    public function product()
    {
        return $this->belongsToMany(Product::class, 'dish_ingredients')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('isActive', true);
    }
}
