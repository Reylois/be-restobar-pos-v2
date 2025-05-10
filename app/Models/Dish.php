<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

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
}
