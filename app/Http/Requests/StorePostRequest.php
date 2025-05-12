<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'             => ['required' , 'string' , 'min:10' , 'max:100'] , 
            'description'       => ['required' , 'string' , 'min:10' , 'max:100'] , 
            'small_description' => ['required' , 'string' , 'min:10' , 'max:100'] , 
            'images'            => ['required' , 'array'] , 
            'images.*'          => ['image' , 'mimes:png,jpg,webp' , 'max:2000'] , 
            'category_id'       => ['required' , 'exists:categories,id'] , 
            'comment_able'      => ['required' , 'in:1,0' , 'boolean'] ,  
        ];
    }
}
