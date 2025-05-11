<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use App\Notifications\ForgetPasswordNotification;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function forgetPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email' , $request->email)->first() ; 
        $user->notify(new ForgetPasswordNotification()) ; 
        return apiResponse(200 , 'Check Your Inbox , Otp Send!') ; 
    }
}
