<?php

namespace App\Providers;

use Illuminate\Http\Request; 
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RatelimitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
       // Home Routes Rate Limiting:
       RateLimiter::for('home' , function(Request $request){
           return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())->response(function(){
              return apiResponse(429 , 'Too Many Attempts!') ; 
           });
       }) ;

       // Contacts Routes RateLimiting
       RateLimiter::for('contacts' , function(Request $request){
           return Limit::perMinute(1)->by($request->ip())->response(function(){
               return apiResponse(429 , 'Too Many Attempts!') ; 
           }) ; 
       }) ; 

       // Account Protected Routes RateLimiting:
       RateLimiter::for('account' , function(Request $request){
           return Limit::perMinute(60)->by($request->user()->id)->response(function(){
               return apiResponse(429 , 'Too Many Attempts!') ; 
           }) ; 
       }) ; 

       // Forgot And Reset Password Routes RateLimitig: 
       RateLimiter::for('password-reset' , function(Request $request){
          return Limit::perMinute(1)->by($request->ip())->response(function(){
             return apiResponse(429 , 'Too Many Attempts!') ; 
          }) ; 
       }) ; 

       // Login Route RateLimitig:
       RateLimiter::for('login' , function(Request $request){
          return Limit::perMinute(5)->by($request->ip())->response(function(){
              return apiResponse(429 , 'Too Many Attempts!') ; 
          }) ; 
       }) ; 

       // Register Route RateLimitig:
       RateLimiter::for('register' , function(Request $request){
           return Limit::perMinute(2)->by($request->ip())->response(function(){
                return apiResponse(429 , 'Too Many Attempts!') ; 
           }) ; 
       }) ; 

       // Refresh Token Route RateLimitig:
       RateLimiter::for('refresh-token' , function(Request $request){
           return Limit::perMinute(2)->by($request->user()->id)->response(function(){
                return apiResponse(429 , 'Too Many Attempts!') ; 
           }) ; 
       });

       // Register Route RateLimitig:
       RateLimiter::for('verify-email' , function(Request $request){
           return Limit::perMinute(2)->by($request->user()->id)->response(function(){
                return apiResponse(429 , 'Too Many Attempts!') ; 
           }) ; 
       }) ; 

       // User-Profile Route RateLimitig:
       RateLimiter::for('user-profile' , function(Request $request){
           return Limit::perMinute(60)->by($request->user()->id)->response(function(){
                return apiResponse(429 , 'Too Many Attempts!') ; 
           }) ; 
       }) ; 


    }
}
