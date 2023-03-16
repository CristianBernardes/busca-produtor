<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'O campo e-mail deve ser preenchido!',
            'password.required' => 'O campo senha deve ser preenchido!',
        ];
    }

    /**
     * @return array[]
     */
    // public function bodyParameters(): array
    // {
    //     return [
    //         'email' => [
    //             'description' => 'E-mail do usuário para realizar login',
    //             'required' => true,
    //             'example' => "pele@magazineaziul.com.br"
    //         ],
    //         'password' => [
    //             'description' => 'Senha do usuário para realizar login',
    //             'required' => true,
    //             'example' => "mudar123"
    //         ],
    //     ];
    // }

}
