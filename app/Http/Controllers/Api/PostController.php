<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

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
}
