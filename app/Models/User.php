<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens , HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username', 
        'email_verified_at' ,
        'provider_id', 
        'phone' , 
        'country' ,
        'city' ,
        'street' ,
        'image', 
        'status' ,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
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
        return $this->hasMany(Post::class , 'user_id') ; 
    }


    public function comments()
    {
        return $this->hasMany(Comment::class , 'user_id') ;
    }

    
    //==========================================================================//
        //------------------------Local Socpes----------------------------//
    //==========================================================================//

    public function getStatusAttribute()
    {
        return $this->attributes['status'] == 1 ? 'Active' : 'Not Active';
    }
}
