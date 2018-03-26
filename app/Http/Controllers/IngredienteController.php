<?php

namespace App\Http\Controllers;

use App\Ingrediente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class IngredienteController extends Controller
{
    public function listAll()
    {
        $ingrediente = Ingrediente::all();

        return response()->json(['data' => $ingrediente]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            $qtd = $request['qtd'];
            $page = $request['page'];
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
            $ingrediente = Ingrediente::paginate($qtd);
            $ingrediente = $ingrediente->appends(Request::capture()->except('page'));
            return response()->json(['data' => $ingrediente]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
            $dados = $request->all();

            // Faz a validacao do ingrediente
            $erros = $this->validacoes($dados);

            if (empty($erros)) {

                $ingrediente = Ingrediente::create($dados);

                if ($ingrediente) {
                    return response()->json(['message' => $ingrediente->nome_ingrediente . " criado com sucesso!"]);
                } else {
                    return response()->json(['message' => 'Dados inválidos.'],400);
                }
            } else {
                return response()->json(['data' => $erros],400);
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
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'],400);
            }

            $ingrediente = Ingrediente::find($id);

            if ($ingrediente) {
                return response()->json(['data' => $ingrediente]);
            } else {
                return response()->json(['message' => 'Ingrediente não encontrado.'],404);
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
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'],400);
            }

            $ingrediente = Ingrediente::find($id);
            $dados = $request->all();

            $erros = $this->validacoes($dados);

            if (empty($erros)) {
                if ($ingrediente) {
                    $ingrediente->update($dados);
                    return response()->json(['message' => $ingrediente->nome_ingrediente . " editado com sucesso!"]);
                } else {
                    return response()->json(['message' => 'Ingrediente não encontrado.'],404);
                }
            } else {
                return response()->json(['data' => $erros],400);
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
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'],400);
            }

            $ingrediente = Ingrediente::find($id);

            if ($ingrediente) {
                $ingrediente->delete();
                return response()->json(['message' => $ingrediente->nome_ingrediente . ' removido com sucesso!']);
            } else {
                return response()->json(['message' => 'Ingrediente não encontrado.'],404);
            }
       
    }

    // Validacoes pro ingrediente
    public function validacoes($dados)
    {
        $erros = [];

        if (strlen($dados['nome_ingrediente']) > 60) {
            array_push($erros, "Nome do ingrediente deve ter no máximo 60 digitos.");
        }
        if (strlen($dados['nome_ingrediente']) == 0 || strlen($dados['nome_ingrediente']) == null) {
            array_push($erros, "Insira o nome do ingrediente.");
        }

        if ($dados['quantidade_calorica_ingrediente'] == null || empty($dados['quantidade_calorica_ingrediente'])) {
            array_push($erros, "Insira a quantidade calorica do ingrediente.");
        }
        if ($dados['aproveitamento_ingrediente'] == null || empty($dados['aproveitamento_ingrediente'])) {
            array_push($erros, "Insira o aproveitamento do ingrediente.");
        }

        return $erros;
    }
}
