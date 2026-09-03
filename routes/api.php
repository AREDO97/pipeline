<?php

use App\Http\Controllers\api\UserController;
use App\Http\Controllers\auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// register user
Route::post('/register',[AuthController::class,'register'])
->name('register');
// login user
Route::post('/login',[AuthController::class,'login'])
->name('login');
// log out endpoint
Route::post('/logout',[AuthController::class,'logout'])
->middleware('auth:sanctum')->name('logout');

// access one user
Route::get('/users/{user}',[UserController::class,'oneUser']);
// update
Route::post('/user/update/{user}',[UserController::class,'update'])
->middleware('auth:sanctum');

// admin delete and update role

Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
     // all users
    Route::get('/users',[UserController::class,'index']);
    // soft delete user
    Route::post('/users/delete/{user}', [UserController::class, 'softDelete']);
    //create admin
    Route::post('/users/create_admin/{user}', [UserController::class, 'makeAdmin']);
    // demote admin
    Route::post('/users/demote_admin/{user}', [UserController::class, 'demoteAdmin']);
    //viewSuspended
    Route::get('/users/suspended', [UserController::class, 'viewSuspended']);
    // unsuspend user
     Route::post('/users/unsuspend/{user}', [UserController::class, 'unsuspend']);
    
});