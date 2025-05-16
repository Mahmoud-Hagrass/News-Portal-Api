<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::guard()->check()){
            return apiResponse(401 , 'Please Login!') ; 
        }
        if(Auth::guard('sanctum')->check() && Auth::guard('sanctum')->user()->email_verified_at == null){
                return apiResponse(401 , 'Please Verify Your Email!') ;
        }
        return $next($request);
    }
}
