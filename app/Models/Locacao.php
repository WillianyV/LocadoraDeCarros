<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $table = 'locacoes';
    protected $fillable = ['data_inicio_periodo', 'data_final_previsto_periodo', 'data_final_realizado_periodo', 'valor_diaria', 'km_inicial', 'km_final', 'cliente_id', 'carro_id'];

    public function rules()
    {            
        return [
            'data_inicio_periodo'          => 'bail|required|date_format:"d/m/Y"',
            'data_final_previsto_periodo'  => 'bail|required|date_format:"d/m/Y"',
            'data_final_realizado_periodo' => 'bail|required|date_format:"d/m/Y"',
            'valor_diaria' => 'bail|required',
            'km_inicial'   => 'bail|required|integer',
            'km_final'     => 'bail|required|integer',
            'cliente_id'   => 'bail|required|exists:clientes,id',
            'carro_id'     => 'bail|required|exists:carros,id',
        ];
    }
}
