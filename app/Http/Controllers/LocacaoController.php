<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocacaoStoreUpdateRequest;
use App\Models\Locacao;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    // injeção do model
    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locacoes = $this->locacao->all();
        return response()->json($locacoes, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LocacaoStoreUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocacaoStoreUpdateRequest $request)
    {
        $locacao = $this->locacao->create([
            'data_inicio_periodo'          => $request->data_inicio_periodo,
            'data_final_previsto_periodo'  => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial'   => $request->km_inicial,
            'km_final'     => $request->km_final,
            'cliente_id'   => $request->cliente_id,
            'carro_id'     => $request->carro_id,
        ]);

        return response()->json([$locacao], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(['erro' => 'Recurso pesquisando não existe.'], 404);
        }
        return response()->json($locacao, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LocacaoStoreUpdateRequest  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(LocacaoStoreUpdateRequest $request, $id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe.'], 404);
        }

        $locacao->update([
            'data_inicio_periodo'          => $request->data_inicio_periodo,
            'data_final_previsto_periodo'  => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial'   => $request->km_inicial,
            'km_final'     => $request->km_final,
            'cliente_id'   => $request->cliente_id,
            'carro_id'     => $request->carro_id,
        ]);
        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe.'], 404);
        }
        $locacao->delete();
        return response()->json(['msg' => 'A locação foi removido com sucesso.'], 200);
    }
}
