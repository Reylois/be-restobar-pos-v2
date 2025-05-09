<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_type',
        'created_at'
    ];

    public $timestamps = false;

    public function orderHistory() {
        return $this->hasMany(OrderHistory::class);
    }
}
