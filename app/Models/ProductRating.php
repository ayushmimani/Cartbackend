<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use  App\Models\ProductRating;
use  APP\Models\Product;

class ProductRating extends Model
{
    protected $fillable = ['user_id', 'product_id','rating'];
    
    public function Product(){
        $this->belongsTo(Product::class);
    }

    public function User(){
        $this->belongsTo(User::class);
    }
}
