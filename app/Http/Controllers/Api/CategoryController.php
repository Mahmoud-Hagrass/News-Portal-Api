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
        return apiResponse(200,'success', new CategoryResource($category)) ; 
    }
}
