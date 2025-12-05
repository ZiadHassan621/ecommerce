<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   protected $fillable = ['name','description','price','stock','status'];

    public static function boot() {
        parent::boot();
        static::saving(function($model){
            $model->status = ($model->stock <= 0) ? 'out_of_stock' : 'available';
        });
    }
}
