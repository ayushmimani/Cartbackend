<?php

namespace App\Models;

use App\Models\User;
use App\Models\cart;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{

    Protected $fillable = ['productname', 'price', 'description', 'image','user_id'];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Cart(){
        return $thid->hasMany(Cart::class, 'product_id');
    }

    

    
}