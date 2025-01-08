<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Product;

class Cart extends Model
{
    //
    use Hasfactory;

    Protected $table = 'carts';

    protected $fillable = ['user_id','quantity','product_id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    
}
