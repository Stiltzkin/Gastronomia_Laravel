<?php

namespace App\Http\Controllers;

use App\Classificacao;
use Illuminate\Http\Request;

class ClassificacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classificacao = Classificacao::all();

        return response()->json(['data' => $classificacao, 'status' => true]);
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

        if (empty($erros)) {
            $classificacao = Classificacao::create($dados);
            if ($classificacao) {
                return response()->json(['data' => $dados, 'status' => true]);
            } else {
                return response()->json(['data' => 'Não foi possível adicionar a classiicação.', 'status' => false]);
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
        $classificacao = Classificacao::find($id);

        if ($classificacao) {
            return response()->json(['data' => $dados, 'status' => true]);
        } else {
            return response()->json(['data' => 'Classificação não existe.', 'status' => false]);
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
        $dados = $request->all();
        $classificacao = Classificacao::find($id);

        $erros = $this->validacoes($dados);

        if (empty($erros)) {
            if ($classificacao) {
                $classificacao = update($dados);
                return response()->json(['data' => $dados, 'status' => true]);
            } else {
                return response()->json(['data' => 'Não foi possível atualizar a classiicação.', 'status' => false]);
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
        $classificacao = Classificacao::find($id);

        if ($classificacao) {
            $classificacao->delete();
            return response()->json(['data' => $dados, 'status' => true]);
        } else {
            return response()->json(['data' => 'Não foi possível adicionar a classiicação.', 'status' => false]);
        }
    }

    public function validacoes($dados)
    {
        $erros = [];

        if (strlen($dados['descricao_classificacao']) == 0 || strlen($dados['descricao_classificacao']) == 0) {
            array_push($erros, "Insira o nome da classificação.");
        }
        if (strlen($dados['descricao_classificacao']) == 60) {
            array_push($erros, "A classificação pode ter no máximo 60 digitos.");
        }
        return $erros;
    }
}
