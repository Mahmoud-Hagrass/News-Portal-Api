<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
                    'post_title'                => $this->title , 
                    'post_slug'                 => $this->slug , 
                    'post_status'               => $this->status ,
                    'post_created_date'         => $this->created_at->format('Y-m-d H:m a') ,
                    'publisher'                 => ($this->user_id == null) ? new AdminResource($this->whenLoaded('admin')) :  new UserResource($this->whenLoaded('user')) , 
                    'images'                    => PostImageResource::collection($this->whenLoaded('images')) , 
                ] ; 
        
        if($request->is('api/posts/show/*')){
            $data['category']                   =  new CategoryResource($this->whenLoaded('category')) ;
            $data['post_small_description']     = $this->small_description ; 
            $data['post_number_of_views']       = $this->number_of_views ;
            $data['post_comment_able']          = $this->comment_able ;
        }

       return $data ; 
    }
}
