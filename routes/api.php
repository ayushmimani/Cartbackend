<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;

//store data 
Route::post('/users', [UserController::class, 'store']);

// match credetial
// Route::post('/login', function (Request $request) {
//     $credentials = $request->only('email', 'password');

//     if (Auth::attempt($credentials)) {
//         $user = Auth::user();
        
//         return response()->json([
//             'status' => 'success',
//             'usertype' => $user->usertype,
//             'token' => $user->createToken('API Token')->plainTextToken,
//         ]);
//     }

//     return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
// });

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    // Attempt to authenticate user
    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Generate token
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'usertype' => $user->usertype,
            ],
            'token' => $token,
        ], 200);
    }

    // If authentication fails
    return response()->json([
        'status' => 'error',
        'message' => 'Invalid credentials. Please check your email and password.',
    ], 401);
});

Route::get('/unverified_member',function(){
    $unverifedmember  = User::where('is_verified',0)->where('usertype', 'member')->get();
    return response()->json([
        'status' => 'success',
        'info' =>$unverifedmember
    ]);
});

Route::post('/verify_member/{id}',function($id){
    $member = User::find($id);
    if($member && $member->usertype==='member'){
        $member->is_verified=1;
        $member->save();
       return  response()->json(['status' => 'success', 'message' => 'Member verified successfully']);
    }
      return response()->json(['status' => 'error', 'message' => 'Member not found'], 404);
});

Route::post('/unverify_member/{id}',function($id){
    $member = User::find($id);
    if($member && $member->usertype==='member'){
        $member->is_verified=-1;
        $member->save();
       return  response()->json(['status' => 'success', 'message' => 'Member rejected successfully']);
    }
      return response()->json(['status' => 'error', 'message' => 'Member not found'], 404);
});


Route::post('/showmember',function(){
    $member = User::where('is_verified',true)->where('usertype','member')->get();
     return response()->json([
        'status' => 'success',
        'info' =>$member
     ]);
});

Route::get('/getmemberlist',function(){
    $memberlist  = User::where('usertype','member')->where('is_verified',1)->get();
    return response()->json([
        'status'=>'success',
        'info' =>$memberlist
    ]);
});

// admin dashboard to show member list

Route::get('/member',function(){
    $member = User::where('usertype','member')->where('is_verified',1)->get();
    return response()->json([
        'status'=>'success',
        'info' =>$member
    ]);
});

//admin dashboard to show user list
Route::get('/user',function(){
    $user = User::where('usertype','user')->get();
    return response()->json([
        'status'=>'success',
        'info' =>$user
    ]);
});
// add products

    Route::post('/addproduct', [ProductController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/getuserproductlist', [ProductController::class, 'productuser'])->middleware('auth:sanctum');
    Route::get('/getuserorderlist',[ProductController::class,'orderuser'])->middleware('auth:sanctum');

    Route::get('showproduct',function(){
        $products = Product::all();
        return response()->json([
           'status' => 'success',
           'products'=>$products
        ]);
    });

    Route::post('addtocart',[CartController::class,'addtocart'])->middleware('auth:sanctum');
    Route::get('cartdata',[CartController::class,'cartdata'])->middleware('auth:sanctum');
    Route::post('orderplaced',[CartController::class,'orderplaced'])->middleware('auth:sanctum');
    Route::post('orderdispatched',[CartController::class,'orderdispatched'])->middleware('auth:sanctum');
    Route::post('cartinfo',[CartController::class,'cartinfo'])->middleware('auth:sanctum');
  
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
