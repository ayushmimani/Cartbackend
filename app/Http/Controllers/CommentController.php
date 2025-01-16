<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    //
    public function Addcomment(Request $request){
       $user=Auth::user();
       $CommentValidation = $request->validate([
            'comment'=>'required',
            'product_id'=>'required'
       ]);

        if($CommentValidation){
            $comment = Comment::create([
                'comment'=>$request->comment,
                'user_id'=>$user->id,
                'product_id'=>$request->product_id,
            ]);
            if($comment){
                return  response()->json([
                    'status'=>'success',
                    'info'=>'comment added successfully',
                ],200);
            }else{
                return  response()->json([
                    'status'=>'failed',
                    'info'=>'comment not added',
                ],500);
            } 
        }
    }

    public function Getcomment($id){
         $comment = Comment::where('product_id',$id)->get();
         if($comment){
             return  response()->json([
                'status'=>'success',
                'info'=>$comment,
             ],200);
         }else{
            return  response()->json([
                'status'=>'falied',
                'info'=>'not getting comment,error',
             ],500);
         }
    }
}
