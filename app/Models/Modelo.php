<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs', 'marca_id'];

    public function rules()
    {
        return [
            'nome'          => "bail|required|max:30|unique:modelos,nome,$this->id",
            'imagem'        => 'bail|required|image|mimes:png,jpeg,jpg|max:100',
            'numero_portas' => 'bail|required|integer|digits_between:1,5',
            'lugares'       => 'bail|required|integer|digits_between:1,13',
            'air_bag'       => 'bail|required|bollean|',
            'abs'           => 'bail|required|bollean', 
            'marca_id'      => 'bail|required|exists:marcas,id',
        ];
    }

    public function marca()
    {
        //UM modelo PERTENCE a UMA marca
        return $this->belongsTo(Marca::class);
    }
}
