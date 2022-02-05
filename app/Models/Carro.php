<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = ['placa', 'disponivel', 'km', 'modelo_id'];

    public function rules()
    {
        return [
            'placa'      => "bail|required|max:30|unique:carros,placa,$this->id",
            'km'         => 'bail|required|integer',
            'disponivel' => 'bail|required|boolean',
            'km'         => 'bail|required|integer', 
            'modelo_id'  => 'bail|required|exists:modelos,id',
        ];
    }
}
