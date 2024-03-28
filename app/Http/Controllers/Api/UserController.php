<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints for user authentication"
 * )
 * @OA\Tag(
 *     name="Users",
 *     description="Endpoints for user management"
 * )
 */

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string", format="password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="User Created Successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request: One or more required fields are empty",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="One or more required fields are empty")
     *         )
     *     )
     * )
     */
   
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
        /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string", format="password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="access_token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized: Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Invalid Credentials")
     *         )
     *     )
     * )
     */

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
    
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="User logout",
     *     tags={"Authentication"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Logged out")
     *         )
     *     )
     * )
     */

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Logged out'
            ] ,200);
    }
}
