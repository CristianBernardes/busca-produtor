<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class ClientRequest extends FormRequest
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
        $id = request()->segment(count(request()->segments()));

        return [
            'client_name' => "required|unique:clients,client_name,{$id},id"
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        $clientName = request()->all()['client_name'] ?? 'Nome';

        return [
            'client_name.required' => 'O campo client_name (nome do cliente) deve ser preenchido!',
            'client_name.unique' => "O nome $clientName já esta em uso!",
        ];
    }
}
