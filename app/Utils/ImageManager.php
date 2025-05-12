<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageManager
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function uploadImages($request  , $post , $disk)
    {
        if($request->hasFile('images')){
            $images = $request->file('images') ; 
            foreach($images as $image){
                 self::uploadSingleImage($image , $post , $disk) ; 
            }
        }
    }

    public static function uploadSingleImage($image , $post , $disk)
    {
        $file = Str::uuid() . '.' . time() . '.' . $image->getClientOriginalExtension() ; 
        $path = $image->storeAs('posts' , $file , ['disk' => $disk]) ; 
        $post->images()->create([
            'image' => $path , 
        ]) ;
    }

    public static function deleteImagesFromLocalStorage($post)
    {
        foreach($post->images as $image){
            $path = $image->image ; 
            if(Storage::disk('public')->exists($path)){
                Storage::disk('public')->delete($path)  ;
            }
        }
    }

}
