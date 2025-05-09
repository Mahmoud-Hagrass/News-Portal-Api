<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'website_name'                      =>  $this->site_name,
            'website_email'                     =>  $this->email ,
            'website_phone'                     =>  $this->phone ,
            'website_small_description'         =>  $this->small_description , 
            'website_logo'                      =>  $this->logo , 
            'website_favicon'                   =>  $this->favicon , 
            'website_facebook_link'             =>  $this->facebook , 
            'website_twitter_link'              =>  $this->twitter,
            'website_instagram_link'            =>  $this->instagram , 
            'website_youtube_link'              =>  $this->youtube , 
            'website_address'                   =>  $this->country . ',' . $this->city . ',' . $this->street , 
        ];
    }
}
