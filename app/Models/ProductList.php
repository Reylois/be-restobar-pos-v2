<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'imagePath',
        'price',
        'category',
        'isActive',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
