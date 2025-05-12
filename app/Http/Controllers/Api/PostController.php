<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Utils\ImageManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function getPosts()
    { 
        $query = Post::with(['user', 'category', 'admin', 'images'])
                ->activeUser()
                ->activeCategory()
                ->active();

        $category_with_posts = Category::query()
                ->with(['posts' => function($query){ $query->whereStatus(1); }])
                ->activePosts()
                ->active()
                ->get() ; 

        $all_posts                  = $this->allPosts($query);
        $postsCollection            = $all_posts->getCollection();
        $latest_posts               = $this->latestPosts($postsCollection) ; 
        $oldest_posts               = $this->oldestPosts($postsCollection) ; 
        $most_read_posts            = $this->mostReadPosts($postsCollection) ; 
        $popular_posts              = $this->popularPosts($query) ; 

        if($all_posts->isEmpty()){
            return apiResponse(404 , 'Not Found Any Posts!') ; 
        }
    
        $response = [
            'all_posts'             => (new PostCollection($all_posts))->response()->getData(true),
            'latest_posts'          => new PostCollection($latest_posts),
            'oldest_posts'          => new PostCollection($oldest_posts),
            'most_read_posts'       => new PostCollection($most_read_posts),
            'popular_posts'         => new PostCollection($popular_posts),
            'categories_with_posts' => new CategoryCollection($category_with_posts) ,
        ] ; 
    
        return apiResponse(200 , 'success' , $response) ; 
    
    }

    private function allPosts($query)
    {
        return $query->latest()->paginate(3) ; 
    }

    private function latestPosts($query)
    {
        return $query->sortByDesc('created_at')->take(3)->values();
    }

    private function oldestPosts($query)
    {
        return $query->sortBy('created_at')->take(3)->values();
    }

    private function mostReadPosts($query)
    {
        return $query->sortByDesc('number_of_views')->take(3)->values(); 
    }

    private function popularPosts($query)
    {
        return $query->withCount('comments')->OrderByDesc('comments_count')->take(3)->get();    
    }

    public function showPost($slug)
    {
        $post = Post::query()
                ->active()
                ->activeCategory()
                ->activeUser()
                ->with(['user' , 'admin' , 'images' , 'category'])
                ->whereSlug($slug)
                ->first() ; 
        if(!$post){
            return apiResponse(404,'post not found!') ; 
        }

        return apiResponse(200,'success' , new PostResource($post)) ; 
    }

    public function postsSearch(Request $request)
    {
        if(!$request->query('keyword')){
            return apiResponse(404 , 'Not Found!') ; 
        }
        $all_posts = Post::with(['user', 'category', 'admin', 'images'])
                    ->activeUser()
                    ->activeCategory()
                    ->active()
                    ->whereAny(['title' , 'description'] , 'LIKE' , "%" . $request->query('keyword') . "%")
                    ->paginate(2);
        
        if($all_posts->isEmpty()) {
            return apiResponse(404 , 'Not Found Any Posts!') ; 
        } 
        return apiResponse(
                    200 ,
                    'success' ,
                    (new PostCollection($all_posts))->response()->getData(true)
        ) ;    
    }

    public function storePost(StorePostRequest $request)
    {
        if(!Auth::guard('sanctum')->check()){
            return apiResponse(401 , 'Invalid Action , Please Login!') ; 
        }
        try{
            DB::beginTransaction() ; 
            $user = Auth::guard('sanctum')->user() ; 
            $data = $request->only(['title' , 'description' , 'small_description' , 'category_id' , 'comment_able']) ; 
            $post = $user->posts()->create($data) ; 

            //upload images :
            ImageManager::uploadImages($request , $post ,'public') ;  
            DB::commit() ; 
            return apiResponse(200 , 'Post Created Successfully!') ; 
        }catch(Exception $e){
            DB::rollBack() ; 
            return apiResponse(500 , 'Try Again!') ; 
        }
    }

    public function deletePost($slug)
    {
        try{
            $post = Post::with('images')->whereSlug($slug)->first() ; 
            if(!$post){
                return apiResponse(404 , 'Post Not Found!') ; 
            }
            DB::beginTransaction() ; 
            // delete images from local disk :
            ImageManager::deleteImagesFromLocalStorage($post) ; 
            //delete post and it's image paths from db:
            $post->delete() ;  
            DB::commit() ; 
            return apiResponse(200 , 'Post Deleted Successfully!') ; 
         }catch(Exception $e){
            DB::rollBack() ; 
            return apiResponse(500 , 'Try Again!') ; 
        }
    }

    public function updatePost(Request $request, $slug)
    {
        try{
            $post = Post::whereSlug($slug)->with('images')->active()->first() ; 
            if(!$post){
                return apiResponse(404 , 'Post Not Found!') ; 
            }
            DB::beginTransaction() ; 
            $data = $request->only(['title' , 'description' , 'small_description' , 'comment_able' , 'category_id']) ; 
            // update post :
            $post->update($data) ; 

            // Delete Image paths From DB : 
            $post->images()->delete() ; 

            // Delete Images Itself From Local Storage :
            ImageManager::deleteImagesFromLocalStorage($post) ;

            // upload New Images For This Post : 
            ImageManager::uploadImages($request , $post , 'public') ; 

            DB::commit() ; 
        }catch(Exception $e){
            DB::rollBack() ; 
            return apiResponse(500 , 'Try Again!') ; 
        }
    }

    public function getPost($slug)
    {
        $post = Post::whereSlug($slug)->first() ;
        if(!$post){
            return apiResponse(500 , 'Try Again!') ; 
        }
        $post_with_data = $post->load(['images' , 'comments' , 'category' , 'user' , 'admin']) ; 
        return apiResponse(200 , 'success' ,new PostResource($post_with_data)) ; 
    }
}
