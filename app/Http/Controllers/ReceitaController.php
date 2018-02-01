<?php

namespace App\Http\Controllers;

use App\Receita;
use Illuminate\Http\Request;

class ReceitaController extends Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $receita = Receita::all();
        return response()->json(['data' => $receita, 'status' => true]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dados = $request->all();
        $erros = $this->validacoes($dados);

        if (empty($erros)) {

            $receita = Receita::create($dados);

            if ($receita) {
                $receita->ingredientes()->sync((array) $request->ingredientes);
                return response()->json(['data' => $dados, 'status' => true]);
            } else {
                return response()->json(['data' => 'Erro ao criar receita.', 'status' => false]);
            }
        } else {
            return response()->json(['data' => $erros, 'status' => false]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $receita = Receita::find($id);

        if ($receita) {
            return response()->json(['data' => $receita, 'status' => true]);
        } else {
            return response()->json(['data' => 'Receita não encontrado.', 'status' => false]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $receita = Receita::find($id);
        $dados = $request->all();

        $erros = $this->validacoes($dados);

        if (empty($erros)) {
            if ($receita) {
                $receita->update($dados);
                $receita->ingredientes()->sync((array) $request->ingredientes);
                return response()->json(['data' => 'Receita atualizada com sucesso.', 'status' => true]);
            } else {
                return response()->json(['data' => 'Não foi possível atualizar a receita', 'status' => false]);
            }
        } else {
            return response()->json(['data' => $erros, 'status' => false]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $receita = Receita::find($id);

        if ($receita) {
            $receita->delete();
            return response()->json(['data' => $receita->nome_receita . ' deletado com sucesso.', 'status' => true]);
        } else {
            return response()->json(['data' => 'Não foi possivel deletar a receita', 'status' => false]);
        }
    }

    public function validacoes($dados)
    {
        $erros = [];

        if (strlen($dados['nome_receita']) == 0 || strlen($dados['nome_receita']) == null) {
            array_push($erros, "Insira o nome da receita.");
        }
        if (strlen($dados['nome_receita']) > 60) {
            array_push($erros, "Nome da receita deve ter no máximo 60 digitos.");
        }

        return $erros;
    }
}
