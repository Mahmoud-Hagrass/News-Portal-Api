<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getAllNotifications()
    {
        if(!Auth::guard('sanctum')->check()){
            return apiResponse(400 , 'Please Login!') ; 
        }
        $all_notifications = Auth::guard('sanctum')->user()->notifications ; 

        if($all_notifications->isEmpty()){
            return apiResponse(404 , 'No Notifications Found!') ; 
        }

        return apiResponse(200 , 'success' , new NotificationCollection($all_notifications)) ; 
    }

    public function getUnreadNotifications()
    {
        if(!Auth::guard('sanctum')->check()){
            return apiResponse(400 , 'Please Login!') ; 
        }
        $unreadNotifications = Auth::guard('sanctum')->user()->unreadNotifications ; 

        if($unreadNotifications->isEmpty()){
            return apiResponse(404 , 'No Notifications Found!') ; 
        }

        return apiResponse(200 , 'success' , new NotificationCollection($unreadNotifications)); 
    }

    public function markSingleNotificationAsRead(Request $request)
    {
        $this->validateNotificationId($request) ; 

        if(!$request->has('notification_id')){
            return apiResponse(400 , 'Invalid Action!') ; 
        }
        $notification = Auth::guard('sanctum')->user()->unreadNotifications()->where('id' ,  $request->notification_id)->first() ; 

        if(!$notification){
            return apiResponse(404 , 'No Notification Found!') ;
        }

        $notification->markAsRead() ;

        return apiResponse(200 , 'Notification Is Read Successfully!') ; 
    }

    public function markAllNotificationsAsRead()
    {
        if(!Auth::guard('sanctum')->check()){
            return apiResponse(400 , 'Please Login!') ; 
        }
        
        $all_unread_notifications = Auth::guard('sanctum')->user()->unreadNotifications ;   
        
        if($all_unread_notifications->isEmpty()){
            return apiResponse(404 , 'No Notifications Found!') ; 
        }

        $all_unread_notifications->markAsRead() ; 
        return apiResponse(200 , 'All Notifications Marked As Read Successfully!') ; 
    }

    public function deleteSingleNotification(Request $request)
    {
       $this->validateNotificationId($request) ; 

       if(!$request->has('notification_id')){
            return apiResponse(400 , 'Invalid Action!') ;   
       }

       if(!Auth::guard('sanctum')->check()){
            return apiResponse(400 , 'Please Login!') ; 
       }

       $notification = Auth::guard('sanctum')->user()->notifications()->where('id' , $request->notification_id)->first() ;

       if(!$notification){
          return apiResponse(404 , 'No Notification Found!') ; 
       }

       $notification->delete() ; 

       return apiResponse(200 , 'Notification Deleted Successfully!') ; 
    }


    private function validateNotificationId($request)
    {
        $request->validate([
            'notification_id'  => ['required','string' , 'max:255'] , 
        ]) ; 
    }

    public function deleteAllNotifications()
    {
        if(!Auth::guard('sanctum')->check()){
            return apiResponse(400 , 'Please Login!') ; 
        }

        $notifications = Auth::guard('sanctum')->user()->notifications ; 
        
        if($notifications->isEmpty()){
             return apiResponse(404 , 'No Notifications Found!')  ; 
        }

        $notifications->each->delete() ; 

        return apiResponse(200 , 'All Notifications Deleted Succcessfully!')  ; 
    }
}
