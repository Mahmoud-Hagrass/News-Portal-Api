<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->hasHeader('x-api-key')){
            return apiResponse(401 , 'Api Key Is Required!') ; 
        }
        if($request->header('x-api-key') !== config('app.api_key')){
            return apiResponse(403 , 'Invalid Api Key!');
        }

        return $next($request);
    }
}
