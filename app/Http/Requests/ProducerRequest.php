<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class ProducerRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'producer_name' => 'required',
            'city' => 'required',
            'state' => 'required',
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
            'volume_in_liters' => 'required',
            'whatsapp_phone' => ['nullable', 'regex:/^[\d\+\(\)\-]+$/']
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'producer_name.required' => 'O nome do produtor é um campo obrigatório!',
            'city.required' => 'A cidade do produtor é um campo obrigatório!',
            'state.required' => 'A UF do produtor é um campo obrigatório!',
            'latitude.required' => 'A latitude do produtor é um campo obrigatório!',
            'latitude.numeric' => 'A latitude do produtor deve ser um valor númerico!',
            'latitude.between' => 'A latitude do produtor deve estar entre -90 e 90!',
            'longitude.required' => 'A longitude do produtor é um campo obrigatório!',
            'longitude.numeric' => 'A longitude do produtor deve ser um valor númerico!',
            'longitude.between' => 'A longitude do produtor deve estar entre -180 e 180!',
            'volume_in_liters.required' => 'O volume em litros do produtor é um campo obrigatório!',
            'whatsapp_phone.regex' => 'O campo telefone deve conter apenas números e os sinais + ( ) -'
        ];
    }
}
