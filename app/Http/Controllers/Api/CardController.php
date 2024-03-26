<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CardRequest;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class CardController extends Controller
{
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


    public function store(Request $request)
    {
        $validator =  $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'phonenumber' => 'required|digits:10',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
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
    public function update(Request $request, $id){
        $validator =  $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'phonenumber' => 'required|digits:10',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);
        $validator['user_id'] = auth()->user()->id;
        $card = Card::find($id);
       
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
    
    public function destroy($id){
        $card = Card::find($id);
        if($card){
            $card->delete();
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Error, Not Found!'
            ], 404);
        }
    }
}
