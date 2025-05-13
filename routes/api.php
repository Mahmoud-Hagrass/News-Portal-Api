<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
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
    Route::post('/logout' , [AuthController::class, 'logout']) ; 
    Route::get('/refresh-token' , [AuthController::class, 'refreshToken'])->middleware('ability:'.TokenAbility::REFRESH_TOKEN->value) ; 
    Route::post('/email/verify' , [AuthController::class, 'verifyEmail']) ; 
    Route::get('/user/profile', [AuthController::class , 'getUserProfile']);
}) ;


Route::post('/forget-password' , [ForgotPasswordController::class , 'forgetPassword']) ; 
Route::post('/reset-password' , [ResetPasswordController::class , 'resetPassword']) ; 


Route::prefix('account')->controller(PostController::class)->middleware('auth:sanctum')->group(function(){
    Route::prefix('/posts')->group(function(){
        Route::get('{slug}/show' , 'getPost') ; 
        Route::post('/store' , 'storePost') ; 
        Route::put('/{slug}/update' , 'updatePost'); 
        Route::delete('/{slug}/delete' , 'deletePost') ; 

        Route::prefix('comments')->controller(CommentController::class)->group(function(){
            Route::post('/' , 'addComment') ; 
            Route::get('/{slug}' , 'getPostComments') ; 
            Route::delete('/{commentId}/delete' , 'deletePostComment') ; 
        }) ; 
    }) ; 
}) ; 
