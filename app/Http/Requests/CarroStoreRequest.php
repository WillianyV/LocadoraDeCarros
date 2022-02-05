<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarroStoreRequest extends FormRequest
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
            'placa'      => "bail|required|max:30|unique:carros",
            'km'         => 'bail|required|integer',
            'disponivel' => 'bail|required|boolean',
            'km'         => 'bail|required|integer', 
            'modelo_id'  => 'bail|required|exists:modelos,id',
        ];
    }
}
