<?php

namespace App\Http\Controllers;

use App\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }
    /**
     * Display a listing of the response.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoria = Categoria::all();
        return response()->json(['data' => $categoria]);
    }

    /**
     * Store a newly created response in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $dados = $request->all();

            // $erros = $this->validacoes($erros);

            if (empty($erros)) {
                $categoria = Categoria::create($dados);

                if ($categoria) {
                    return response()->json(['data' => $categoria], 201);
                } else {
                    return response()->json(['message' => 'Dados inválidos.'], 400);
                }
            } else {
                return response()->json(['data' => $erros], 400);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
        }
    }

    /**
     * Display the specified response.
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

            $categoria = Categoria::find($id);

            if ($categoria) {
                return response()->json(['data' => $categoria], 200);
            } else {
                return response()->json(['message' => 'Categoria não existe.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
        }
    }

    /**
     * Update the specified response in storage.
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

            $dados = $request()->all();
            $categoria = Categoria::find($id);

            $erros = $this->validacoes($dados);

            if (empty($erros)) {
                if ($categoria) {
                    $categoria->update($dados);
                    return response()->json(['data' => $categoria], 204);
                } else {
                    return response()->json(['message' => 'Categoria não existe.'], 404);
                }
            } else {
                return response()->json(['data' => $erros], 400);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
        }
    }

    /**
     * Remove the specified response from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if ($id < 0) {
                return response()->json(['data' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }

            $categoria = Categoria::find($id);

            if ($categoria) {
                $categoria->delete();
                return response()->json(['data' => $categoria], 200);
            } else {
                return response()->json(['message' => 'Categoria não existe.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
        }
    }

    public function validacoes($dados)
    {
        $erros = [];

        if (strlen($dados['descricao_categoria']) == 0 || strlen($dados['descricao_categoria']) == null) {
            array_push($erros, "Insira o nome da categoria.");
        }
        if (strlen($dados['descricao_categoria']) > 60) {
            array_push($erros, "O nome da categoria pode ter no máximo 60 digitos.");
        }
        return $erros;
    }
}
