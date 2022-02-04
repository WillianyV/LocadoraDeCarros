<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarcaStoreRequest;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    // injeção do model
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = $this->marca->all();
        return response()->json($marcas, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MarcaStoreRequest $request)
    {
        // Gravar a foto e pegando o caminho onde ela foi salva.
        $imagem  = $request->file('imagem');
        $path    = "imagens/marcas/$request->nome";
        $retorno = $imagem->store($path,'public');

        $marca = $this->marca->create([
            'nome'   => $request->nome,
            'imagem' => $retorno
        ]);

        return response()->json([$marca], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisando não existe.'], 404);
        }
        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe.'], 404);
        }

        if($request->method() === 'PATCH'){
            /**
             * quando quero atualizar só algumas coisas utiliza-se o PATCH,
             * quando quero atualizar todos os dados é PUT
             */
            $regrasDinamicas = array();
            foreach ($marca->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            // Validando os dados através do modelo
            $request->validate($regrasDinamicas, $marca->feedback());
        }else{
            // Validando os dados através do modelo
            $request->validate($marca->rules(), $marca->feedback());
        }

        //remove o arquivo antigo, caso um novo tenha sido enviado no request
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        // Gravar a foto e pegando o caminho onde ela foi salva.
        $imagem  = $request->file('imagem');
        $path    = "imagens/marcas/$request->nome";
        $retorno = $imagem->store($path,'public');

        $marca->update([
            'nome'   => $request->nome,
            'imagem' => $retorno
        ]);
        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe.'], 404);
        }
        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso.'], 200);
    }
}
