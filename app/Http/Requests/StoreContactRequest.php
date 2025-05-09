<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
            'name'          => ['required' , 'string' , 'min:3' , 'max:30'] , 
            'email'         => ['required' , 'string' , 'lowercase' , 'email:filter', 'min:10' , 'max:50' , 'unique:contacts,email' , 'ends_with:gmail.com'] , //regex:/^@(gamil\.com|yahoo\.com)$/
            'phone'         => ['required' , 'string'  ,'min:11' , 'max:11' , 'regex:/^01[0-2,5]{1}[0-9]{8}$/'] ,  
            'subject'       => ['required' , 'string' , 'min:30' , 'max:200'] ,
            'message'       => ['required' , 'string' , 'min:30' , 'max:2000'] ,  
            'address'       => ['required' , 'string' , 'min:10' , 'max:40']  , 
        ];
    }

    public function messages()
    {
        return [
            //'email.regex' =>      'not supported!'
              'email.ends_with' => 'Only (@gmail) Domain Is Supported' , 
        ] ; 
    }
}
