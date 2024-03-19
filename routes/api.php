<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatMessageController;
use Illuminate\Support\Facades\Broadcast;


// active ll route khas bl api : api/broadcasting/auth
Broadcast::routes(['middleware' =>['auth:sanctum']]);


Route::post('/api-online',function(){
    dd('We Are Online now !');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register-user',[AuthController::class,'register']);
Route::post('/login-user',[AuthController::class,'login']);
Route::group(['middleware'=>'auth:sanctum'],function(){
    Route::post('/login-user-WithToken',[AuthController::class,'loginWithToken']);
    Route::post('/logout-user',[AuthController::class,'logout']);
});
// Route::post('/login-user-WithToken',[AuthController::class,'loginWithToken'])->middleware('auth:sanctum');
// Route::post('/logout-user',[AuthController::class,'logout'])->middleware('auth:sanctum');




Route::middleware('auth:sanctum')->group(function (){

    Route::apiResource('chat', ChatController::class)->only([
        'index',
        'store',
        'show'
    ]);
    Route::apiResource('chat_message', ChatMessageController::class)->only([
        'index',
        'store'
    ]);


});