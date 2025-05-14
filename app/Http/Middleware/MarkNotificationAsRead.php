<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MarkNotificationAsRead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(request()->query('notify')){
            $notification_id = request()->query('notify') ;  
            if(!$notification_id){
                return apiResponse(400 , 'Invalid Action!') ; 
            }
            if(!Auth::guard('sanctum')->check()){
                return apiResponse(401 , 'Please Login!') ;
            }
            $notification = Auth::guard('sanctum')->user()->unreadNotifications()->where('id' , $notification_id)->first() ; 
            if(!$notification){
                return apiResponse(404 , 'No Notification Found!') ; 
            }
            $notification->markAsRead() ; 
        }
        return $next($request);
    }
}
