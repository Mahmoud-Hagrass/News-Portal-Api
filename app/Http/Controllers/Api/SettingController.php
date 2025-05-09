<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getOrCreateSetting()
    {
       $site_settings = Setting::firstOr(function(){
           return Setting::create([
                'site_name'                 => 'news-portal' ,
                'email'                     => 'news@news.com',
                'phone'                     => '01020274921',
                'small_description'         => 'this is news app', 
                'logo'                      => null ,
                'favicon'                   => null ,
                'facebook'                  => 'www.facebook.com',
                'twitter'                   => 'www.twitter.com',
                'instagram'                 => 'wwww.instagram.com',
                'youtube'                   => 'www.youtube.com',
                'country'                   => 'Egypy',
                'city'                      => 'Cairo',
                'street'                    => 'Fasil',
           ]) ;
       }) ; 

       if(!$site_settings){
           return apiResponse(404 , 'No Site Settings Founded!') ; 
       }
       return apiResponse(200 , 'success' , new SettingResource($site_settings)) ; 
    }
}
