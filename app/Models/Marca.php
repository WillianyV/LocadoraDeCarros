<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    // Fazer a validaçõa dos dados em update
    public function rules()
    {
        return [
            'nome'   => "bail|required|max:50|unique:marcas,nome,$this->id",
            'imagem' => 'bail|required|image|mimes:png|max:100',
        ];
    }

    public function feedback()
    {
        return [
            'required'    => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da marca já existe',
            'nome.max'    => 'O nome pode ter no máximo 50 caracteres',
            'imagem.max'  => 'A imagem pode ter no máximo 100 caracteres',
            'imagem.mimes'=> 'A imagem só pode ser do tipo pnj.',
        ];
    }

    public function modelos()
    {
        //UMA marca POSSUI MUITOS modelos
        return $this->hasMany(Modelo::class);
    }

}
