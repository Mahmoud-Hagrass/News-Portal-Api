<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory ;

    protected $fillable = ['name' , 'username' , 'email' , 'password', 'status' , 'role_id' , 'remember_token' , 'email_verified_at'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function casts():array
    { 
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    } 

    //==========================================================================//
        //------------------------Relationships----------------------------//
    //==========================================================================//

    public function posts()
    {
        return $this->hasMany(Post::class, 'admin_id');
    }
    
     //==========================================================================//
        //------------------------Accessors----------------------------//
    //==========================================================================//

    public function getStatusAttribute()
    {
        return $this->attributes['status'] == 1 ? 'Active' : 'Not Active' ; 
    }

}