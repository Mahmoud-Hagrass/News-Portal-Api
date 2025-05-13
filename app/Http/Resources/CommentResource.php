<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'comment'   => $this->comment , 
            'comment_owner' => new UserResource($this->whenLoaded('user'))  , 
            'created_date' => $this->created_at->format('Y-m-d H:m a') , 
            'status'    => $this->status , 
        ];
    }
}
