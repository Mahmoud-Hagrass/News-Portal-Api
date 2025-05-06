<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Comment extends Model
{
    use HasFactory , HasApiTokens;
    
    protected $fillable = [
            'comment' ,
            'user_id' ,
            'post_id' ,
            'status' ,
            'ip_address'
      ] ; 

    
      //==========================================================================//
        //------------------------Relationships----------------------------//
    //==========================================================================//
    
      public function user()
      {
          return $this->belongsTo(User::class) ;
      }

      public function post()
      {
         return $this->belongsTo(Post::class) ; 
      }

    //==========================================================================//
        //------------------------Local Socpes----------------------------//
    //==========================================================================//

    public function scopeActive($query)
    {
        return $query->where('status' , 1) ; 
    }
}