<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'small_description' , 
        'favicon',
        'logo',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'country',
        'city',
        'street',
        'email',
        'phone',
    ] ; 
}
