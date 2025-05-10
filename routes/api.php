<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RelatedSiteLinkController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\UserController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


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

Route::post('/contacts' , [ContactController::class , 'storeContact']) ; 



Route::prefix('/auth')->middleware('auth:sanctum')->group(function(){
    Route::post('/register' , [AuthController::class , 'register'])->withoutMiddleware('auth:sanctum')  ; 
    Route::post('/login' , [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum') ; 
    Route::get('/refresh-token' , [AuthController::class, 'refreshToken'])->middleware('ability:'.TokenAbility::REFRESH_TOKEN->value) ; 
    Route::get('/user/profile', [AuthController::class , 'getUserProfile']);
}) ;