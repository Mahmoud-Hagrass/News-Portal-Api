<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Category extends Model
{
    use HasFactory, Sluggable , HasApiTokens;


    protected $fillable = ['name', 'slug', 'status'];


    //==========================================================================//
    //------------------------Relationships----------------------------//
    //==========================================================================//

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }


    //==========================================================================//
    //------------------------Elequent Sluggable----------------------------//
    //==========================================================================//

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name' , 
                'onUpdate' => true ,    
            ]
        ];
    }

     //==========================================================================//
           //------------------------Accessors----------------------------//
    //==========================================================================//

    public function getStatusAttribute()
    {
        return $this->attributes['status'] == 1 ? 'Active' : 'Not Active' ;
    }

}