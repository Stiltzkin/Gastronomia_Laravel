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
        return response()->json(['data' => $classificacao]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $dados = $request->all();

            if (empty($erros)) {
                $classificacao = Classificacao::create($dados);
                if ($classificacao) {
                    return response()->json(['message' => $classificacao->descricao_classificacao . " criado com sucesso!"]);
                } else {
                    return response()->json(['message' => 'Dados inválidos.']);
                }
            } else {
                return response()->json(['data' => $erros]);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.');
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
        try {
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.']);
            }

            $classificacao = Classificacao::find($id);

            if ($classificacao) {
                return response()->json(['data' => $classificacao]);
            } else {
                return response()->json(['message' => 'Classificação não existe.']);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor');
        }
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
        try {
            if ($id < 0) {
                return response()->json(['data' => 'ID menor que zero, por favor, informe um ID válido.']);
            }

            $dados = $request->all();
            $classificacao = Classificacao::find($id);

            $erros = $this->validacoes($dados);

            if (empty($erros)) {
                if ($classificacao) {
                    $classificacao = update($dados);
                    return response()->json(['message' => $classificacao->descricao_classificacao . " editado com sucesso!"]);
                } else {
                    return response()->json(['message' => 'Classificação não encontrada.']);
                }
            } else {
                return response()->json(['data' => $erros]);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.');
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
        try {
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.']);
            }

            $classificacao = Classificacao::find($id);

            if ($classificacao) {
                $classificacao->delete();
                return response()->json(['data' => $classificacao->descricao_classificacao . " deletado com sucesso!"]);
            } else {
                return response()->json(['message' => 'Classificação não encontrada.']);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor');
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
