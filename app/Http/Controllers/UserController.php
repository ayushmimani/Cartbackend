<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function store(Request $request)
{

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|',
        'usertype'=>'required|string'
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'usertype' => $validated['usertype'],
        'password' => bcrypt($validated['password']),
    ]);

    return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
}

}
