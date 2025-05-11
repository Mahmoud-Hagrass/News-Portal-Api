<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
            'email'  => ['required' , 'string'  , 'email:filter' , 'lowercase' , 'exists:users,email' , 'min:15' , 'max:60' , 'ends_with:gmail.com'], 
        ];
    }

    public function messages(): array
    {
        return [
            'email.ends_with'  => 'This Email Domain Not Supported , Only Gmail Domain Is Supported!', 
            'email.exists'     => 'Invalid Email!' , 
        ];
    }
}
