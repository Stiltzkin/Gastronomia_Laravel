<?php

namespace App\Http\Controllers;

use App\Ingrediente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IngredienteController extends Controller
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
        $ingrediente = Ingrediente::all();
        return response()->json(['data' => $ingrediente, 'status' => true]);
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
                return response()->json(['data' => $ingrediente, 'status' => true]);
            } else {
                return response()->json(['data' => 'Erro ao criar ingrediente.', 'status' => false]);
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
        $ingrediente = Ingrediente::find($id);

        if ($ingrediente) {
            return response()->json(['data' => $ingrediente, 'status' => true]);
        } else {
            return response()->json(['data' => 'Ingrediente não encontrado.', 'status' => false]);
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
        $ingrediente = Ingrediente::find($id);
        $dados = $request->all();

        $erros = $this->validacoes($dados);

        if (empty($erros)) {
            if ($ingrediente) {
                $ingrediente->update($dados);
                return response()->json(['data' => $ingrediente, 'status' => true]);
            } else {
                return response()->json(['data' => 'Não foi possível atualizar o ingrediente.', 'status' => false]);
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
        $ingrediente = Ingrediente::find($id);

        if ($ingrediente) {
            $ingrediente->delete();
            return response()->json(['data' => $ingrediente->nome_ingrediente . ' removido com sucesso!', 'status' => true]);
        } else {
            return response()->json(['data' => 'Não foi possível deletar o ingrediente', 'status' => false]);
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
