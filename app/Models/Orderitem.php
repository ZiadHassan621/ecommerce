<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderitem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
'quantity',
'price',
'order_id',
'product_id'
    ];
    public function order() {
        return $this->belongsTo(Order::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
