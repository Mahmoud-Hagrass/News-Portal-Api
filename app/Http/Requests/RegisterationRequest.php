<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterationRequest extends FormRequest
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
            'name'                  => ['required' , 'string' , 'min:3' , 'max:30'], 
            'username'              => ['required' , 'string' , 'min:3' , 'unique:users,username'] , 
            'email'                 => ['required' , 'string' , 'email' , 'lowercase' , 'min:15' , 'max:60' , 'email:filter'  , 'unique:users,email' , 'ends_with:gmail.com'], 
            'password'              => ['required' , 'string' , 'min:8' , 'max:40' , 'confirmed'], 
        ];
    }

    public function messages(): array
    {
        return [
            'email.ends_with'       => 'Only Gmail Domain Is Supported!' , 
        ];
    }
}
