<?php

namespace App\Http\Controllers;

use App\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
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
                    return response()->json(['message' => $categoria->descricao_categoria . " criado com sucesso!"]);
                } else {
                    return response()->json(['message' => 'Dados inválidos.']);
                }
            } else {
                return response()->json(['message' => $erros]);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.');
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
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.']);
            }

            $categoria = Categoria::find($id);

            if ($categoria) {
                return response()->json(['data' => $categoria]);
            } else {
                return response()->json(['message' => 'Categoria não existe.']);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.');
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
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.']);
            }

            $dados = $request()->all();
            $categoria = Categoria::find($id);

            $erros = $this->validacoes($dados);

            if (empty($erros)) {
                if ($categoria) {
                    $categoria->update($dados);
                    return response()->json(['message' => $categoria->descricao_categoria . " editado com sucesso!"]);
                } else {
                    return response()->json(['message' => 'Categoria não existe.']);
                }
            } else {
                return response()->json(['message' => $erros]);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.');
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
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.']);
            }

            $categoria = Categoria::find($id);

            if ($categoria) {
                $categoria->delete();
                return response()->json(['message' => $categoria->descricao_categoria . " deletado com sucesso!"]);
            } else {
                return response()->json(['message' => 'Categoria não existe.']);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.');
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
