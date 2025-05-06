<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Post extends Model
{
    use HasFactory , Sluggable , HasApiTokens ;

    protected $fillable = [
        'title',
        'description',
        'slug' , 
        'number_of_views' , 
        'comment_able',
        'status' ,
        'user_id',
        'category_id',
        'small_description' , 
    ];


     //==========================================================================//
        //------------------------Relationships----------------------------//
    //==========================================================================//
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class) ; 
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class) ; 
    }

    public function images()
    {
        return $this->hasMany(PostImage::class , 'post_id'); 
    }

    public function comments()
    {
        return $this->hasMany(Comment::class , 'post_id');
    }
     //==========================================================================//
        //------------------------Elequent Sluggable----------------------------//
    //==========================================================================//
    
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title' , 
                'onUpdate' => true ,
            ]
        ];
    }


     //==========================================================================//
        //------------------------Local Scopes---------------------------//
    //==========================================================================//
    
    // Local Scope To Get Active Posts
    public function scopeActive($query)
    {
        return $query->where('status', 1); ; 
    }

    public function scopeActiveUser($query)
    {
        $query->where(function($query){
            $query->whereHas('user' , function($user){
                $user->whereStatus(1) ; 
            })->orWhere('user_id' , null);
        }) ; 
    }

    public function scopeActiveCategory($query)
    {
        return $query->whereHas('category' , function($category){
            $category->whereStatus(1) ; 
        }) ;
    }


     //==========================================================================//
        //------------------------Accessotrs----------------------------//
    //==========================================================================//
    
    public function getStatusAttribute()
    {
        return $this->attributes['status'] == 1 ? 'Active' : 'Not Active' ;
    }

    public function getCommentAbleAttribute()
    {
        return $this->attributes['comment_able'] == 1 ? 'Active' : 'Not Active' ; 
    }
}