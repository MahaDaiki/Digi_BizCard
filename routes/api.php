<?php

use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::post('logout',[UserController::class,'logout'])
  ->middleware('auth:sanctum');

Route::get('cards',[CardController::class,'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('cards', [CardController::class, 'store']);
    Route::get('cards/{id}', [CardController::class, 'show']);
    Route::get('cards/{id}/edit',[CardController::class,'edit']);
    Route::put('cards/{id}', [CardController::class, 'update']);
    Route::delete('cards/{id}', [CardController::class, 'destroy']);
});

