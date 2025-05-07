<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('posts')->controller(PostController::class)->group(function(){
    Route::get('/' , 'getPosts') ;  
    Route::get('/show/{slug}' , 'showPost') ; 
}) ;  

Route::get('/categories' , [CategoryController::class , 'getCategories']) ; 
Route::get('/category/{slug}' , [CategoryController::class, 'getCategory']) ; 
