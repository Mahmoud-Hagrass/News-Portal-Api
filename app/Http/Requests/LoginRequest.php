<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email'           => ['required' , 'string' , 'email' , 'lowercase' , 'email:filter' , 'ends_with:gmail.com'], 
            'password'        => ['required' , 'string' , 'min:8' , 'max:40'], 
        ];
    }
}
