<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentCollection;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $comment_with_data = $comment->load(['user' , 'post']) ; 

        if($post->user_id != Auth::guard('sanctum')->user()->id){
            $post->user->notify(new NewCommentNotification($comment_with_data)) ;
        }

        if(!$comment){
            return apiResponse(400 , 'Invalid Action!') ; 
        }

        return apiResponse(200 , 'Comment Created Successfully!') ; 
    }

    public function getPostComments($slug)
    {
        $post = Post::whereSlug($slug)->activeCategory()->first() ; 
        if(!$post){
            return apiResponse(404 , 'Post Not Found!') ; 
        }

        $comments_with_user = $post->comments()
        ->with(['user' => function($query){
            $query->where('status' , 1) ;
        }])
        ->activeUser()
        ->active()
        ->get() ; 

        if($comments_with_user->isEmpty()){
            return apiResponse(404 , 'This Post Dosn\'t Have Comments!') ; 
        } 
        return apiResponse(200 , 'successs' , new CommentCollection($comments_with_user)) ; 
    }

    public function deletePostComment(Request $request , $commentId)
    {
        $comment = Comment::find($commentId) ; 
        if(!$comment){
            return apiResponse(404 , 'Comment Not Found!') ;
        }

        if($comment->user_id != $request->user_id && $comment->post->user_id != $request->user_id){
            return apiResponse(400 , 'Invalid Action!') ; 
        }

        $comment->delete() ; 
        return apiResponse(200 , 'Comment Deleted Successfully!') ;

    }
}
