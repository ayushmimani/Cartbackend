<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CartController;

class ProductController extends Controller
{
    //

    public function store(Request $request){
            //    print_r($request->all());exit;

       $user= Auth::user();
      
       if(!$user){
        return response()->json(['error' => 'User not authenticated'], 401);
       }
        $validatedata =$request->validate([
            'productname'=>'required',
            'description'=>'required',
            'price'=>'required',
            'image'=>'required',
        ]);

        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $user = Auth::user();

      
        Product::create([
            'productname' => $validatedata['productname'],
            'description'=>$validatedata['description'],
            'price'=>$validatedata['price'],
            'image' =>$imagePath ?? null,
            'user_id'=>$user->id
        ]);

         return response()->json(['success','product add successfully'],201);
    }

    public function productuser(){
       $user = Auth::user();
       $products = Product::where('user_id',$user->id)->get();
       return response()->json($products, 200);
    }

    public function orderuser(){
        $user = Auth::user();

    // Fetch products linked to carts with order_placed = true
    $products = DB::table('products')
    ->join('carts', 'products.id', '=', 'carts.product_id')
    ->where('carts.order_placed', true)
    ->where('products.user_id', $user->id)
    ->select('products.*', 'carts.quantity','carts.order_dispatched') // Select all product columns and quantity
    ->get();

    return response()->json($products, 200);
    }
}
