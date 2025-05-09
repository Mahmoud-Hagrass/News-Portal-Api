<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RelatedSiteLinkController;
use App\Http\Controllers\Api\SettingController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('posts')->controller(PostController::class)->group(function(){
    Route::get('/search' , 'postsSearch') ; 
    Route::get('/{slug}' , 'showPost') ; 
    Route::get('/' , 'getPosts') ;  
}) ;  

Route::prefix('categories')->controller(CategoryController::class)->group(function(){
    Route::get('/' , 'getCategories') ; 
    Route::get('/{slug}' ,'getCategory') ; 
}) ; 

Route::get('/site-settings' , [SettingController::class , 'getOrCreateSetting']) ;

Route::get('/related-site-links' , [RelatedSiteLinkController::class, 'getRelatedSiteLinks']) ;