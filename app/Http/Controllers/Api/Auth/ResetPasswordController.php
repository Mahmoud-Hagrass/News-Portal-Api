<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public $otp ; 

    public function __construct()
    {
        $this->otp = new Otp() ;     
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email' , $request->email)->first() ; 
        if(!$user){
            return apiResponse(404 , 'Not Found!') ; 
        }

        $otpStatus = $this->otp->validate($user->email , $request->token) ;  
        if($otpStatus === false){
            return apiResponse(400 , 'Invalid Token!') ; 
        }

        $user->update([
            'password' => Hash::make($request->password) , 
        ]) ; 
        return apiResponse(200 , 'Reset Password Sucessfully!') ; 
    }
}
