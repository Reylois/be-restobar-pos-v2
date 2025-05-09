<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_list_id',
        'subtotal',
        'payment_method',
        'amount_paid',
        'discount',
        'created_at',
    ];

    public $timestamps = false;

    public function sale() 
    {
        return $this->belongsTo(Sale::class);
    }

    public function productList() 
    {
        return $this->belongsTo(ProductList::class);
    }
}
