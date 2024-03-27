<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;


class UserController extends Controller
{
    public function register(Request $request){

        if (empty($request->name) || empty($request->email) || empty($request->password)) {
            return response()->json([
                'status' => 400,
                'message' => 'One or more required fields are empty',
            ], 400);
        }
        $registerUserData = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|min:8'
        ]);
        $user = User::create([
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
        ]);
        if($user){
            return response()->json([
                'status' => 200,
                'message' => 'User Created Successfully',
            ],200);
        } 
        else {
            return response()->json([
                'status' => 500,
                'message' => 'Error'
            ], 500);
        }
    }
    
    public function login(Request $request){
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required'
        ]);
        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
            return response()->json([
                'status' => 401,
                'message' => 'Invalid Credentials'
            ],401);
        }
        else {$token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        return response()->json([
            'status' => 200,
            'access_token' => $token,
        ],200);
    }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Logged out'
            ] ,200);
    }
}
