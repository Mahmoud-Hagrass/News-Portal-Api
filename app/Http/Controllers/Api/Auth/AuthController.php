<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Enums\TokenAbility;
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterationRequest;
use App\Http\Resources\UserResource;
use App\Notifications\SendOtpVerificationMail;
use Carbon\Carbon;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public $otp ; 
    
    public function __construct()
    {
        $this->otp = new Otp() ;     
    }
    
    public function register(RegisterationRequest $request)
    {
        $data = [
                    'name'      => $request->name ,
                    'username'  => $request->username,
                    'email'     => $request->email ,
                    'password'  => Hash::make($request->password) , 
                ] ; 

        $user   = User::create($data) ; 
        if(!$user){
            return apiResponse(400 , 'Invalid Action, try again!') ; 
        }

        $event = new UserRegistered($user) ; 
        event($event) ; 

        return apiResponse(201 ,
                    'User Registered Successfully!' ,
                    [
                       'access_token'  => $event->access_token , 
                       'refresh_token' => $event->refresh_token , 
                    ]
            ) ; 
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email' , $request->email)->first() ; 
        if(!$user || !Hash::check($request->password , $user->password)){
            return apiResponse(400 , 'Invalid Action, Try Again!')  ;
        }

        $access_token   = $user->createToken('access-token' , [TokenAbility::ACCESS_TOKEN->value] ,   Carbon::now()->addMinutes(config('sanctum.access_token_expiration')))->plainTextToken ;
        $refresh_token  = $user->createToken('refresh-token' , [TokenAbility::REFRESH_TOKEN->value] , Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration')))->plainTextToken ;

        return apiResponse(200 , 
                           'Login Successfully!',
                            [
                                'access_token'  => $access_token , 
                                'refresh_token' => $refresh_token , 
                            ]
         ) ; 
    }

    public function logout(Request $request)
    {
        $user = $request->user() ; 
        if(!$user){
            return apiResponse(400 , 'Invalid Action!') ; 
        }
        $user->currentAccessToken()->delete() ; 
        return apiResponse(200 , 'User Loggedout Successfully!') ; 
    }

    public function getUserProfile()
    {
        if(!Auth::guard('sanctum')->check()){
            return apiResponse(400, 'Invalid Action, Try Again!') ; 
        }

        return apiResponse(200 , 'success' , new UserResource(auth()->user())) ; 
    }

    public function refreshToken()
    {
        if(!Auth::check()){
            return apiResponse(400, 'Invalid Action, Try Again!') ; 
        }
        
        $user = Auth::guard('sanctum')->user() ; 

        // delete the refresh token for user when the access token is expired 
        // in this method will create from refresh-token (access-token for sanctum middleware)
        // using this refresh token will create new (access-token and refresh-token)
        $user->currentAccessToken()->delete() ; 

        $access_token   = $user->createToken('access-token' , [TokenAbility::ACCESS_TOKEN->value] , Carbon::now()->addMinutes(config('sanctum.access_token_expiration')))->plainTextToken ;
        $refresh_token  = $user->createToken('refresh-token' , [TokenAbility::REFRESH_TOKEN->value] , Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration')))->plainTextToken ;

        return apiResponse(200 ,
                    'success' ,
                    [
                       'access_token'  => $access_token , 
                       'refresh_token' => $refresh_token , 
                    ]
            ) ; 
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'token' => ['required' , 'string' , 'min:6' , 'max:6'] , 
        ]) ; 

        $user = $request->user() ;
        $token = $request->token ; 

        $otp2 = $this->otp->validate($user->email , $token) ; 
        if($otp2->status === false){
            return apiResponse(400 , 'Invalid OTP Code!') ; 
        }

        $user->update([
            'email_verified_at' => Carbon::now() , 
        ]) ;
        return apiResponse(200 , 'Email Verified Successfully') ; 
    }
}
