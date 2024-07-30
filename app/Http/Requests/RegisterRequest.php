<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
    public function rules()
    {
        return [
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|min:3',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter your name',
            'name.min' => 'Your name must be at least 3 characters long',
            'email.required' => 'Please enter a valid email address',
            'password.required' => 'Please provide a password',
            'password.min' => 'Your password must be at least 6 characters long',
            'password.confirmed' => 'Password and confirmation do not match',
        ];
    }

}
