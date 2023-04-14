<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 *
 */
class UserRequest extends FormRequest
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
        return $this->rulesAndMessages()['rules'];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return $this->rulesAndMessages()['messages'];
    }

    /**
     * @return array
     */
    private function rulesAndMessages(): array
    {
        $id = request()->segment(count(request()->segments()));

        $email = request()->all()['email'] ?? 'Email';

        $rules = [
            'profile_user' => 'required', Rule::in(['administrator', 'client']),
            'name' => 'required',
            'email' => "required|unique:users,email,{$id},id",
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90'
            ],
            'longitude' => [
                'required',
                'numeric',
                'between:-180,180'
            ],
            'state' => 'required',
            'city' => 'required'
        ];

        $messages = [
            'profile_user.required' => 'O campo profile_user (Perfil de usuário) deve ser preenchido! (Os valores permitidos são administrator ou  client)',
            'name.required' => 'O campo name (nome do usuário) deve ser preenchido!',
            'email.required' => 'O campo email do usuário deve ser preenchido!',
            'email.unique' => "O email $email já esta em uso!",
            'latitude.required' => 'A latitude do usuário é um campo obrigatório!',
            'latitude.numeric' => 'A latitude do usuário deve ser um valor númerico!',
            'latitude.between' => 'A latitude do usuário deve estar entre -90 e 90!',
            'longitude.required' => 'A longitude do usuário é um campo obrigatório!',
            'longitude.numeric' => 'A longitude do usuário deve ser um valor númerico!',
            'longitude.between' => 'A longitude do usuário deve estar entre -180 e 180!',
            'state.required' => 'O campo state (UF do usuário) deve ser preenchido!',
            'city.required' => 'O campo city (cidade do usuário) deve ser preenchido!',
        ];

        $profileUser = request()->all()['profile_user'] ?? null;

        if ($profileUser && $profileUser == 'client') {
            $rules['client_id'] = 'required';
            $messages['client_id.required'] = 'O campo client_id (Id do cliente) deve ser preenchido!';
        }

        return [
            'rules' => $rules,
            'messages' => $messages
        ];
    }
}
