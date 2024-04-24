<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CardRequest;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

/**
 * @OA\Tag(
 *     name="Cards",
 *     description="Endpoints for card management"
 * )
 */
class CardController extends Controller
{   
     /**
     * @OA\Get(
     *     path="/api/cards",
     *     summary="Get all cards",
     *     tags={"Cards"},
     *     @OA\Response(
     *         response=200,
     *         description="Returns all cards",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="cards", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No cards found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="No cards found")
     *         )
     *     )
     * )
     */
  
    public function index(){
        $Cards = Card::all();
        if ($Cards->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No cards found'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'cards' => $Cards
        ], 200);
        
    }
/**
 * @OA\Post(
 *     path="/api/cards/add",
 *     summary="Create a new card",
 *     tags={"Cards"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"logo", "title", "phonenumber", "email"},
 *             @OA\Property(property="logo", type="string", format="binary"),
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="slogan", type="string"),
 *             @OA\Property(property="phonenumber", type="string", example="1234567890"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="address", type="string"),
 *             @OA\Property(property="website", type="string", format="uri"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Card added successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Card Added Successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="message", type="string", example="Error")
 *         )
 *     )
 * )
 */


    public function store(Request $request)
    {
        $validator =  $request->validate([
            'logo' => 'nullable',
            'title' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'phonenumber' => 'required|digits:10',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|max:255',
        ]);
        $validator['user_id'] = auth()->user()->id;
        $card = Card::create($validator);
        if ($card) {
            return response()->json([
                'status' => 200,
                'message' => "Card Added Successfully"
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Error'
            ], 500);
        }
    }
     /**
     * @OA\Get(
     *     path="/api/cards/{id}",
     *     summary="Get a card by ID",
     *     tags={"Cards"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the card",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Returns the card",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="card", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found!",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Not Found!")
     *         )
     *     )
     * )
     */
    public function show($id){
        $card = Card::find($id);
        if($card){
            return response()->json([
                'status' => 200,
                'card' => $card
            ], 200);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => 'Not Found!'
            ], 404);
        }
    }
 
    public function edit($id){
        $card = Card::find($id);
        if($card){
            return response()->json([
                'status' => 200,
                'card' => $card
            ], 200);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => 'Not Found!'
            ], 404);
        }
    }
/**
 * @OA\Put(
 *     path="/api/cards/{id}/update",
 *     summary="Update a card",
 *     tags={"Cards"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the card",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"logo", "title", "phonenumber", "email"},
 *             @OA\Property(property="logo", type="string", format="binary"),
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="slogan", type="string"),
 *             @OA\Property(property="phonenumber", type="string", example="1234567890"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="address", type="string"),
 *             @OA\Property(property="website", type="string", format="uri"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Card updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Card Updated Successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Error, Not Found!",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Error, Not Found!")
 *         )
 *     )
 * )
 */
    public function update(Request $request, $id){
  $card = Card::find($id);
  $card = Card::find($id);
  if (!$card) { 
      return response()->json([
          'status' => 404,
          'message' => 'Error, Not Found!'
      ], 404);
  }
  if (auth()->user()->id !== $card->user_id) {
      return response()->json([
          'status' => 403,
          'message' => 'Unauthorized, You are not allowed to update this card!'
      ], 403);
  }
        $validator =  $request->validate([
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'string|max:255',
            'slogan' => 'string|max:255',
            'phonenumber' => 'numeric',
            'email' => 'email|max:255',
            'address' => 'string|max:255',
            'website' => 'max:255',
        ]);
 
      
       
        if ($card) { 
            $card->update($validator);
            return response()->json([
                'status' => 200,
                'message' => "Card Updated Successfully"
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Error, Not Found!'
            ], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/cards/{id}/destroy",
     *     summary="Delete a card",
     *     tags={"Cards"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the card",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Card deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Deleted Successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Error, Not Found!",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Error, Not Found!")
     *         )
     *     )
     * )
     */
    public function destroy($id){
        $card = Card::find($id);
        if (auth()->user()->id !== $card->user_id) {
            return response()->json([
                'status' => 403,
                'message' => 'Unauthorized, You are not allowed to update this card!'
            ], 403);
        }
        elseif($card){
            $card->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Deleted Successfully'
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Error, Not Found!'
            ], 404);
        }
    }
    /**
 * @OA\Get(
 *     path="/api/cards/user",
 *     summary="Get cards by authenticated user ID",
 *     tags={"Cards"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Returns the user's cards",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="cards", type="array", @OA\Items(type="object"))
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No cards found for the user",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="No cards found for the user")
 *         )
 *     )
 * )
 */
public function showAuthenticatedUserCard()
{
    $userId = Auth::id();
    if (!$userId) {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    $businessCard = Card::where('user_id', $userId)->get();
    
    if (!$businessCard) {
        return response()->json(['error' => ' No cards found for authenticated user.'], 404);
    }
    
    return response()->json($businessCard, 200);
}

public function countcards(){
    // $users = User::count();
$cards = Card::count();
// $users = User::get();
// $user= $users->id;
// $cards = Card::where('user_id', $user)->count();
$card = Card::join('users','users.id','=','cards.user_id')->selectRaw('user_id ,count(user_id)')
              ->groupBy('user_id')->get();
 return response()->json($card, 200);
}
}