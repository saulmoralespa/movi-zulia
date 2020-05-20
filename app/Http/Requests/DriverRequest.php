<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|numeric|unique:driver',
            'plate_number' => 'required|unique:driver'
        ];
    }

    public function messages()
    {
        return [
            'phone.unique' => 'El :attribute ya sido registrado',
            'phone.numeric' => 'Ingrese solo números para el campo celular',
            'plate_number.unique' => 'El :attribute ya sido registrado',
        ];
    }
}
