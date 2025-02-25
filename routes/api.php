<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function Pest\Laravel\json;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function(){

    Route::get('/blogs',[BlogController::class,'getAllBlogs']);
    Route::post('/blogs/create',[BlogController::class,'createBlog']);
    Route::patch('/blogs/update/{id}',[BlogController::class,'updateBlog']);
    Route::delete('/blogs/delete/{id}',[BlogController::class,'deleteBlog']);
    Route::post('/logout',[UserController::class,'logout']);
});

Route::post('/login',[UserController::class,'login']);
Route::post('/register',[UserController::class,'register']);