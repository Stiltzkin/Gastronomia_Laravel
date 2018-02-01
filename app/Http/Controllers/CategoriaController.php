<?php

namespace App\Http\Controllers;

use App\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoria = Categoria::all();

        return response()->json(['data' => $categoria, 'status' => true]);
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

        $erros = $this->validacoes($erros);

        if (empty($erros)) {
            $categoria = Categoria::create($dados);

            if ($categoria) {
                return resource()->json(['data' => $dados, 'status' => true]);
            } else {
                return resource()->json(['data' => 'Não foi possível criar a categoria.', 'status' => false]);
            }
        } else {
            return resource()->json(['data' => $erros, 'status' => false]);
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
        $categoria = Categoria::find($id);

        if ($categoria) {
            return resource()->json(['data' => $categoria, 'status' => true]);
        } else {
            return resource()->json(['data' => 'Categoria não existe.', 'status' => false]);
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
        $dados = $request()->all();
        $categoria = Categoria::find($id);

        $erros = $this->validacoes($dados);

        if (empty($erros)) {
            if ($categoria) {
                $categoria->update($dados);
                return resource()->json(['data' => $categoria, 'status' => true]);
            } else {
                return resource()->json(['data' => 'Não foi possível atualizar a categoria', 'status' => false]);
            }
        } else {
            return resource()->json(['data' => $erros, 'status' => false]);
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
        $categoria = Categoria::find($id);

        if ($categoria) {
            $categoria->delete();
            return resource()->json(['data' => $categoria, 'status' => true]);
        } else {
            return resource()->json(['data' => 'Não foi possível deletar a categoria.', 'status' => false]);
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
