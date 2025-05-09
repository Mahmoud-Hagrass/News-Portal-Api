<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $categories = Category::query()
            ->with([
                    'posts' => function($query){
                        $query->with(['user' , 'admin' , 'images'])->whereStatus(1); 
                    }
                ])
            ->activePosts()
            ->active()
            ->get() ; 
        
        if($categories->isEmpty() || !$categories){
            return apiResponse(404 , 'Not Found Any Posts!') ; 
        } 
        
        return apiResponse(200,'success' , new CategoryCollection($categories)); 
    }

    public function getCategory($slug)
    {
        $category = Category::query()
            ->with(['posts' => function($query){
                   $query->with(['user' , 'admin' , 'images'])->whereStatus(1) ; 
              }])
            ->whereSlug($slug)
            ->active()
            ->activePosts()
            ->first() ;

        if($category->isEmpty() || !$category){
            return apiResponse(404 , 'Not Found!') ;
        }
        return apiResponse(200,'success', new CategoryResource($category)) ; 
    }
}
