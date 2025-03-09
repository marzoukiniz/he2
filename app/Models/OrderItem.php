<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'variation_id', 'quantity', 'price'];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function variation(){
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }
}
