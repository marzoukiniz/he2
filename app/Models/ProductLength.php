<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLength extends Model
{
    protected $fillable = ['product_id', 'color_id', 'length', 'additional_cost', 'stock'];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function color(){
        return $this->belongsTo(ProductColor::class, 'color_id', 'color_id');
    }
}
