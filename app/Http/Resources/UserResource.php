<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'name'                  => $this->name , 
            'user_image'            => ($this->image) == null ? asset('def-img.jpg') : asset('/storage' . $this->image) , 
            'user_status'           => $this->status , 
        ];

        if($request->is('api/auth/user/profile')){
            $data['user_name']      =  $this->username ;
            $data['phone']          =  $this->phone ;
            $data['country']        =  $this->country ;
            $this['city']           =  $this->city ;
            $this['street']         =  $this->street ;
            $this['image']          =  $this->image ; 
        }

        return $data ; 
    }
}
