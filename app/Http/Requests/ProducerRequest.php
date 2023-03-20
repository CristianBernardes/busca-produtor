<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'producer_name' => 'required',
            'city' => 'required',
            'state' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'volume_in_liters' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'producer_name.required' => 'O nome do produtor é um campo obrigatório!',
            'city.required' => 'A cidade do produtor é um campo obrigatório!',
            'state.required' => 'A UF do produtor é um campo obrigatório!',
            'latitude.required' => 'A latitude do produtor é um campo obrigatório!',
            'longitude.required' => 'A longitude do produtor é um campo obrigatório!',
            'volume_in_liters.required' => 'O volume em litros do produtor é um campo obrigatório!',
        ];
    }
}
