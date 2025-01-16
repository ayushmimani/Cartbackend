<?php

namespace App\Models;
use App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    Protected $fillable = ['comment','product_id','user_id'];

    public function Product(){
        $this->belongsTo(Product::class);
    }
}
