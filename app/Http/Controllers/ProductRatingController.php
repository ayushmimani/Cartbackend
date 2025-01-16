<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductRating;
class ProductRatingController extends Controller
{
    public function RateProduct(Request $request){
        $user= Auth::user();
        $productratingValidation = $request->validate([
            'rating'=>'required',
            'product_id'=>'required'
        ]);
        if($productratingValidation){
            $productRating = ProductRating::create([
                'rating'=>$request->rating,
                'product_id'=>$request->product_id,
                'user_id'=>$user->id
            ]);
            
            if($productRating){
                return  response()->json(['success','rating add successfully'],201);
            }else{
                return  response()->json(['failed','error to add rating'],201);
            }
        }

    }

    public function GetRating($id){
         $rating = ProductRating::where('product_id',$id)->get();
       //  dd($rating);
         if($rating){
            return response()->json([
                'status'=>'success',
                'info'=>$rating
            ],200);
         }else{
            return response()->json([
                'status'=>'failed',
                'info'=>'error to getting rating'
            ],500);
         }
    }
}
