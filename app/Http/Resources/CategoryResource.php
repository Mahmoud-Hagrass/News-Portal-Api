<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id , 
            'category_name'      => $this->name , 
            'category_slug'      => $this->slug , 
            'category_status'    => $this->status , 
            'posts'              => PostResource::collection($this->whenLoaded('posts')) , 
        ] ; 
    }
}
