<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemsFactory> */
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class);
    }
    //order has many order items and order item belongs to a product so item.product refers to product of item


    public function order(){
        return $this->belongsTo(Order::class);
    }

}
