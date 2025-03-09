<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $fillable = ['product_id', 'color'];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function lengths(){
        return $this->hasMany(ProductLength::class, 'color_id', 'color_id');
    }
}
