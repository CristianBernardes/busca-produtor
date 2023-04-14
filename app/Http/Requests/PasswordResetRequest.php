<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'password_reset_token' => 'required'
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'email.required' => 'O campo email deve ser preenchido!',
            'password.required' => 'O campo password deve ser preenchido!',
            'password_reset_token.required' => 'O campo password_reset_token deve ser preenchido!',
            'password.confirmed' => 'O campo confirmação de password (password_confirmation) deve ser preenchido!',
            'password.min' => 'O campo password deve ter no mínimo 6 caracteres.',
        ];
    }
}
