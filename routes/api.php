<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\NotificationController;
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
})->middleware('throttle:home') ;  

Route::prefix('categories')->controller(CategoryController::class)->group(function(){
    Route::get('/' , 'getCategories') ; 
    Route::get('/{slug}' ,'getCategory') ; 
})->middleware('throttle:home') ; 

Route::get('/site-settings' , [SettingController::class , 'getOrCreateSetting'])->middleware('throttle:home') ;

Route::get('/related-site-links' , [RelatedSiteLinkController::class, 'getRelatedSiteLinks'])->middleware('throttle:home') ;

Route::post('/contacts' , [ContactController::class , 'storeContact'])->middleware('throttle:contacts') ; 



Route::prefix('/auth')->controller(AuthController::class)->middleware(['auth:sanctum'])->group(function(){
    Route::post('/register'      , 'register')->withoutMiddleware('auth:sanctum')->middleware('throttle:register')  ; 
    Route::post('/login'         , 'login')->withoutMiddleware('auth:sanctum')->middleware('throttle:login') ; 
    Route::post('/logout'        , 'logout') ; 
    Route::get('/refresh-token'  , 'refreshToken')->middleware(['ability:'.TokenAbility::REFRESH_TOKEN->value , 'throttle:refresh-token']) ; 
    Route::post('/email/verify'  , 'verifyEmail')->middleware('throttle:verify-email') ; 
    Route::get('/user/profile'   , 'getUserProfile')->middleware(['verify_email' , 'throttle:user-profile']);
}) ;

/* Reset Password Routes */
Route::post('/forget-password' , [ForgotPasswordController::class , 'forgetPassword'])->middleware('throttle:password-reset') ; 
Route::post('/reset-password' , [ResetPasswordController::class , 'resetPassword'])->middleware('throttle:password-reset') ; 


Route::prefix('account')->controller(PostController::class)->middleware(['auth:sanctum' , 'verify_email' ,'throttle:account'])->group(function(){
    /* Post Routes */
    Route::prefix('/posts')->group(function(){
        Route::get('{slug}/show' , 'getPost') ; 
        Route::post('/store' , 'storePost') ; 
        Route::put('/{slug}/update' , 'updatePost'); 
        Route::delete('/{slug}/delete' , 'deletePost') ; 

        /* Comments Routes */
        Route::prefix('comments')->controller(CommentController::class)->group(function(){
            Route::post('/' , 'addComment') ; 
            Route::get('/{slug}' , 'getPostComments') ; 
            Route::delete('/{commentId}/delete' , 'deletePostComment') ; 
        }) ; 
    }) ; 

    /* Notification Routes */
    Route::prefix('notifications')->controller(NotificationController::class)->group(function(){
        Route::get('/' , 'getAllNotifications') ; 
        Route::get('/unread' , 'getUnreadNotifications') ; 
        Route::post('/mark-as-read' , 'markSingleNotificationAsRead') ;
        Route::get('/mark-all-as-read' , 'markAllNotificationsAsRead') ; 
        Route::delete('/delete' , 'deleteSingleNotification') ;  
        Route::delete('/delete/all' , 'deleteAllNotifications') ;  
    }) ; 
}) ; 
