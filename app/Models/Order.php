<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'sub_total', 'quantity', 'delivery_charge', 'status',
        'total_amount', 'first_name', 'last_name', 'country', 'post_code', 'address1',
        'address2', 'phone', 'email', 'payment_method', 'payment_status', 'shipping_id', 'coupon'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }

    public static function getAllOrder($id)
    {
        return self::with('orderItems')->find($id);
    }

    public static function countActiveOrder()
    {
        return self::count() ?? 0;
    }

    public function cart_info(){
        return $this->hasMany('App\Models\Cart','order_id','id');
    }
    
    public function cart(){
        return $this->hasMany(Cart::class);
    }
}