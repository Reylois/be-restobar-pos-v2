<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public $timestamps = false; // since you're using only `created_at`

    protected $fillable = [
        'subtotal', 
        'discount', 
        'total_amount',
        'order_type', 
        'payment_method', 
        'amount_paid', 
        'created_at'
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
