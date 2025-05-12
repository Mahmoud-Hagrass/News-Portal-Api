<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function addComment(StoreCommentRequest $request)
    {
        $post = Post::find($request->post_id) ;
        if(!$post){
            return apiResponse(404 , 'Post Not Found!') ; 
        }

        $comment = $post->comments()->create([
            'comment'    => $request->comment , 
            'user_id'    => Auth::guard('sanctum')->user()->id , 
            'ip_address' => $request->ip() , 
        ]) ; 

        if(!$comment){
            return apiResponse(400 , 'Invalid Action!') ; 
        }

        return apiResponse(200 , 'Comment Created Successfully!') ; 
    }
}
