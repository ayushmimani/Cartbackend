<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //
//     public function addtocart(Request $request){
//         print_r($request);exit;
//         $user = Auth::user();
//         $validatecart = $request->validate([
//             'quantity'=>'required',
//              'product_id'=>'required'
//         ]);

//         // check if userid and product already exist if yes then update quantity
//         // $checkproductuser = Cart::where('user_id',$user->id)->where('product_id',$request->product_id)
//         // ->where('order_placed',0)->get();
       
//         // if($checkproductuser) return  response()->json(['message'=>'cart updated'],200);

//         $cartItem = Cart::where('user_id', $user->id)
//         ->where('product_id', $request->product_id)
//         ->where('order_placed', 0);
//         if($cartItem){
//             $cartItem->delete();
//         }
//             Cart::create([
//                 'user_id'=>$user->id,
//                 'product_id'=>$request->product_id,
//                 'quantity'=>$request->quantity,
//             ]);
//             return response()->json(['message'=>'added  product in cart'],200);
        
    
// }

public function addtocart(Request $request)
{
    $user = Auth::user();
    // Validate the incoming request
    $validatecart = $request->validate([
        'quantity' => 'required',
        'product_id' => 'required|exists:products,id', // Ensure product exists
    ]);
    //if quantity is zero then delete from cart
    if($request->quantity===0){
        $removefromcart = Cart::where('user_id', $user->id)
        ->where('product_id', $request->product_id)
        ->where('order_placed', 0) // Ensure it's an active cart
        ->first();
        if($removefromcart){
            $removefromcart->delete();
        }
    }
    // Check if the product already exists in the cart for the user
    $cartItem = Cart::where('user_id', $user->id)
        ->where('product_id', $request->product_id)
        ->where('order_placed', 0) // Ensure it's an active cart
        ->first();
    if ($cartItem) {
        // Update the quantity if the item exists
        $cartItem->quantity = $request->quantity; // Add to the existing quantity
        $cartItem->save();
        return response()->json(['message' => 'Cart updated successfully'], 200);
    } else {
        // Create a new cart item if it doesn't exist
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);
        return response()->json(['message' => 'Product added to cart'], 200);
    }
}


    public function cartdata(){

        $user=Auth::user();
        $cartdata=Cart::with('product')->where('user_id', $user->id)->get();
        return response()->json([
            'status'=>'success',
            'info'=>$cartdata
        ]);
    }

    public function orderplaced(Request $request){
      // dd($request->selectedorder);
        $validated = $request->validate([
            'selectorder'=>'require|array',
        ]);
        $user=Auth::user();
        $orderplaced = Cart::where('user_id',$user->id)->whereIn('product_id',$request->selectedorder)->update(['order_placed'=>true]);
        if($orderplaced){
            return response()->json([
                'status'=>'success',
                'info'=>'order placed successfully'
            ],200);
        }else{
            return response()->json([
                'status'=>'error',
                'info'=>'Failed to place the order'
            ],500);
        }
    }

    public function orderdispatched(Request $request){
         $orderdispatched = Cart::where('product_id',$request->id)->update(['order_dispatched'=>true]);
           if($orderdispatched){
              return response()->json([
                'status'=>'success',
              ],200);
           }else{
            return response()->json([
                'status'=>'fail',
            ],500);
           }
        }

      public function cartinfo(Request $request){

        $user=Auth::user();
        $cartvalidate = $request->validate([
            'product_id'=>'required'
        ]);

        if($cartvalidate){
            $cartinfo = Cart::where('product_id', $request->product_id)->where('user_id',$user->id)->get();
            if($cartinfo) {
                return response()->json([
                    'status'=>'success',
                    'info'=>$cartinfo
                  ],200);
            }else{
                return response()->json([
                    'status'=>'failed',
                    'error'=>"not getting info of cart"
                  ],500);
            }
        }
      }  
}
