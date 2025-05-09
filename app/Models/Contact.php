<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name' , 
        'email' , 
        'subject' , 
        'message' ,
        'address' ,
        'phone' , 
        'ip_address' , 
        'is_read' , 
        'created_at' , 
        'update_at'
    ] ; 
}
