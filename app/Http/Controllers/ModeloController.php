<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModeloStoreRequest;
use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    // injeção do model
    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('atributos_marcas')){
            $atributos_marcas = $request->atributos_marcas;
            $modelos = $this->modelo->with("marca:$atributos_marcas");
        }else{
            $modelos = $this->modelo->with('marca');
        }

        if($request->has('pesquisa')){
            $filtros = explode(';', $request->pesquisa);
            foreach ($filtros as $filtro) {
                $pesquisa = explode(':', $filtro);
                $modelos = $modelos->where($pesquisa[0],$pesquisa[1],$pesquisa[2]);   
            }                     
        }

        if($request->has('atributos')){
            $atributos = $request->atributos; //"id,nome,imagem,marca_id" o selectRaw seleciona uma string inteira
            $modelos = $modelos->selectRaw($atributos)->get();
        }else{
            $modelos = $modelos->get();
        }
        
        return response()->json($modelos, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ModeloStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModeloStoreRequest $request)
    {
        // Gravar a foto e pegando o caminho onde ela foi salva.
        $imagem  = $request->file('imagem');
        $folder  = str_replace([' ', '-'], '_', mb_strtoupper($request->nome, 'UTF-8'));
        $path    = "imagens/modelos/$folder";
        $retorno = $imagem->store($path,'public');

        $modelo = $this->modelo->create([
            'nome'          => mb_strtoupper($request->nome, 'UTF-8'),
            'imagem'        => $retorno,
            'numero_portas' => $request->numero_portas,
            'lugares'       => $request->lugares,
            'air_bag'       => $request->air_bag,
            'abs'           => $request->abs,
            'marca_id'      => $request->marca_id,
        ]);

        return response()->json([$modelo], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if ($modelo === null) {
            return response()->json(['erro' => 'Recurso pesquisando não existe.'], 404);
        }
        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe.'], 404);
        }

        if($request->method() === 'PATCH'){
            /**
             * quando quero atualizar só algumas coisas utiliza-se o PATCH,
             * quando quero atualizar todos os dados é PUT
             */
            $regrasDinamicas = array();
            foreach ($modelo->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            // Validando os dados através do modelo
            $request->validate($regrasDinamicas);
        }else{
            // Validando os dados através do modelo
            $request->validate($modelo->rules());
        }

        // preencher o objeto $marca com os dados enviados
        // se tiver algum que não foi ele não atualiza
        $modelo->fill($request->all());

        //remove o arquivo antigo, caso um novo tenha sido enviado no request
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);

            $imagem_rtn  = $request->file('imagem');
       
            $folder  = str_replace([' ', '-'], '_', mb_strtoupper($request->nome, 'UTF-8'));
            $path    = "imagens/modelos/$folder";
            
            $retorno = $imagem_rtn->store($path,'public');

            //insere o novo caminho da imagem
            $modelo->imagem = $retorno;
        }
        
        //atualiza se tiver ID, se não tiver cria um novo
        $modelo->save();

        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe.'], 404);
        }
        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();
        return response()->json(['msg' => 'O modelo foi removido com sucesso.'], 200);
    }
}
