<?php

use App\Http\Controllers\auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// register user
Route::post('/register',[AuthController::class,'register'])
->name('register');
// login user
Route::post('/login',[AuthController::class,'login'])
->name('login');
// log out endpoint
Route::post('/logout',[AuthController::class,'logout'])
->middleware('auth:sanctum')->name('logout');