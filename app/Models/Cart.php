<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'order_id', 'quantity', 'status', 'length_id', 'color_id'];
    
    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // You can also define relationships for length and color if needed
    public function length()
    {
        return $this->belongsTo(ProductLength::class, 'length_id');
    }

    public function color()
    {
        return $this->belongsTo(ProductColor::class, 'color_id');
    }
}
