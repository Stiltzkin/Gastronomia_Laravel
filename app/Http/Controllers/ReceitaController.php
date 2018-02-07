<?php

namespace App\Http\Controllers;

use App\Receita;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

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
    public function index(Request $request)
    {
        try {
            $qtd = $request['qtd'];
            $page = $request['page'];
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
            $receita = Receita::paginate($qtd);
            $receita = $receita->appends(Request::capture()->except('page'));
            return response()->json(['data' => $receita], 200);
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
        }
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
            $erros = $this->validacoes($dados);

            if (empty($erros)) {

                $receita = Receita::create($dados);

                if ($receita) {
                    $receita->ingredientes()->sync((array) $request->ingredientes);
                    return response()->json(['data' => $receita], 201);
                } else {
                    return response()->json(['message' => 'Dados inválidos.'], 400);
                }
            } else {
                return response()->json(['data' => $erros], 400);
            }
        } catch (\Exception $e) {
            return response()->json('Erro no servidor.', 500);
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
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }

            $receita = Receita::find($id);

            if ($receita) {
                return response()->json(['data' => $receita], 200);
            } else {
                return response()->json(['message' => 'Receita não encontrado.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json('Erro no servidor.', 500);
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
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }

            $receita = Receita::find($id);
            $dados = $request->all();

            $erros = $this->validacoes($dados);

            if (empty($erros)) {
                if ($receita) {
                    $receita->update($dados);
                    $receita->ingredientes()->sync((array) $request->ingredientes);
                    return response()->json(['message' => 'Receita atualizada com sucesso.'], 204);
                } else {
                    return response()->json(['message' => 'Receita não encontrado.'], 404);
                }
            } else {
                return response()->json(['data' => $erros], 400);
            }
        } catch (\Exception $e) {
            return response()->json('Erro no servidor.', 500);
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
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }

            $receita = Receita::find($id);

            if ($receita) {
                $receita->delete();
                return response()->json(['data' => $receita->nome_receita . ' deletado com sucesso.', 'status' => true], 200);
            } else {
                return response()->json(['message' => 'Receita não encontrado.', 'status' => false], 404);
            }
        } catch (\Exception $e) {
            return response()->json('Erro no servidor.', 500);
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
